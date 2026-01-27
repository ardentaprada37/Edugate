<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LateAttendanceController extends Controller
{
    public function create($studentId)
    {
        $student = \App\Models\Student::with('schoolClass')->findOrFail($studentId);
        
        // Check if homeroom teacher is accessing their class student only
        if (auth()->user()->isHomeroomTeacher() && auth()->user()->assigned_class_id != $student->class_id) {
            abort(403, 'You can only record attendance for students from your assigned class.');
        }
        
        $lateReasons = \App\Models\LateReason::active()->get();
        
        return view('late-attendance.create', compact('student', 'lateReasons'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'late_reason_id' => 'required|exists:late_reasons,id',
            'late_date' => 'required|date',
            'arrival_time' => 'required',
            'notes' => 'nullable|string',
        ]);
        
        $validated['recorded_by'] = auth()->id();
        $validated['status'] = 'pending';
        
        $telegramService = new \App\Services\TelegramService();
        
        try {
            // Use transaction to ensure data consistency
            \DB::beginTransaction();
            
            // Create late attendance record
            $record = \App\Models\LateAttendance::create($validated);
            
            // Commit transaction first - only send Telegram if DB save succeeds
            \DB::commit();
            
            // Load relationships for Telegram notification
            $recordWithRelations = \App\Models\LateAttendance::with(['student', 'schoolClass', 'lateReason', 'recordedBy'])
                ->find($record->id);
            
            // Send automatic Telegram notification
            try {
                $telegramSent = $telegramService->sendSingleLateNotification($recordWithRelations);
                
                // Update telegram_sent status
                if ($telegramSent) {
                    $record->update([
                        'telegram_sent' => true,
                        'telegram_sent_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                // Log error but don't fail the request - record is already saved
                \Log::error('Telegram notification failed for single late attendance: ' . $e->getMessage());
            }
            
            return redirect()->route('classes.show', $validated['class_id'])
                ->with('success', 'Keterlambatan berhasil dicatat. Notifikasi Telegram dikirim otomatis.');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Late attendance store failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal menyimpan data keterlambatan. Silakan coba lagi.'])->withInput();
        }
    }
    
    public function index(Request $request)
    {
        $query = \App\Models\LateAttendance::with(['student', 'schoolClass', 'lateReason', 'recordedBy']);
        
        // Filter by role
        if (auth()->user()->isHomeroomTeacher()) {
            $query->where('class_id', auth()->user()->assigned_class_id);
        }
        
        // Apply filters
        if ($request->filled('search')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('late_date', $request->date);
        }
        
        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('late_date', $request->month)
                  ->whereYear('late_date', $request->year);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $lateAttendances = $query->orderBy('late_date', 'desc')->paginate(20);
        
        $classes = auth()->user()->isHomeroomTeacher() 
            ? \App\Models\SchoolClass::where('id', auth()->user()->assigned_class_id)->get()
            : \App\Models\SchoolClass::active()->get();
        
        return view('late-attendance.index', compact('lateAttendances', 'classes'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);
        
        $attendance = \App\Models\LateAttendance::findOrFail($id);
        $attendance->update($validated);
        
        return back()->with('success', 'Status updated successfully.');
    }
    
    /**
     * Show multi-student late attendance form with dynamic student selection
     */
    public function multiCreate(Request $request)
    {
        // Get students based on user role
        $studentsQuery = \App\Models\Student::with('schoolClass')->where('is_active', true);
        
        // If homeroom teacher, restrict to their class only
        if (auth()->user()->isHomeroomTeacher()) {
            $studentsQuery->where('class_id', auth()->user()->assigned_class_id);
        }
        
        $students = $studentsQuery->orderBy('name')->get();
        
        $lateReasons = \App\Models\LateReason::active()->get();
        
        return view('late-attendance.multi-create', compact('students', 'lateReasons'));
    }
    
    
    public function multiStore(Request $request)
    {
        $validated = $request->validate([
            'students' => 'required|array|min:1',
            'students.*.student_id' => 'required|exists:students,id',
            'students.*.late_date' => 'required|date',
            'students.*.arrival_time' => 'required',
            'students.*.late_reason_id' => 'required|exists:late_reasons,id',
            'students.*.notes' => 'nullable|string',
        ]);
        
        // Get all students to verify class access
        $studentIds = array_column($validated['students'], 'student_id');
        $students = \App\Models\Student::whereIn('id', $studentIds)->get()->keyBy('id');
        
        if ($students->count() != count($studentIds)) {
            return back()->withErrors(['students' => 'Beberapa siswa tidak valid.'])->withInput();
        }
        
        // If homeroom teacher, verify they can only add students from their class
        if (auth()->user()->isHomeroomTeacher()) {
            foreach ($students as $student) {
                if ($student->class_id != auth()->user()->assigned_class_id) {
                    abort(403, 'You can only record attendance for students from your assigned class.');
                }
            }
        }
        
        $createdRecords = [];
        $telegramService = new \App\Services\TelegramService();
        
        try {
            // Use transaction to ensure data consistency
            \DB::beginTransaction();
            
            // Create late attendance records for each student with their individual data
            foreach ($validated['students'] as $studentData) {
                $student = $students->get($studentData['student_id']);
                
                $record = \App\Models\LateAttendance::create([
                    'student_id' => $studentData['student_id'],
                    'class_id' => $student->class_id,
                    'late_reason_id' => $studentData['late_reason_id'],
                    'late_date' => $studentData['late_date'],
                    'arrival_time' => $studentData['arrival_time'],
                    'notes' => $studentData['notes'] ?? null,
                    'recorded_by' => auth()->id(),
                    'status' => 'pending',
                ]);
                
                $createdRecords[] = $record;
            }
            
            // Commit transaction first - only send Telegram if DB save succeeds
            \DB::commit();
            
            // Load relationships for Telegram notification
            $recordsWithRelations = \App\Models\LateAttendance::with(['student', 'schoolClass', 'lateReason', 'recordedBy'])
                ->whereIn('id', collect($createdRecords)->pluck('id'))
                ->get();
            
            // Send automatic Telegram notification for each record
            try {
                $telegramSent = $telegramService->sendBulkIndividualLateNotification($recordsWithRelations);
                
                // Update telegram_sent status for all records
                if ($telegramSent) {
                    \App\Models\LateAttendance::whereIn('id', collect($createdRecords)->pluck('id'))
                        ->update([
                            'telegram_sent' => true,
                            'telegram_sent_at' => now(),
                        ]);
                }
            } catch (\Exception $e) {
                // Log error but don't fail the request - records are already saved
                \Log::error('Telegram notification failed for multi late attendance: ' . $e->getMessage());
            }
            
            $studentCount = count($createdRecords);
            
            return redirect()->route('late-attendance.index')
                ->with('success', "Berhasil mencatat keterlambatan untuk {$studentCount} siswa. Notifikasi Telegram dikirim otomatis.");
                
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Multi late attendance store failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal menyimpan data keterlambatan. Silakan coba lagi.'])->withInput();
        }
    }
    
    /**
     * Show bulk review page for multiple students (supports multiple classes)
     */
    public function bulkReview(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'nullable|exists:classes,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'existing_form_data' => 'nullable|json', // Accept existing form data
        ]);
        
        // Parse existing form data if provided
        $existingFormData = [];
        $existingStudentIds = [];
        if (!empty($validated['existing_form_data'])) {
            $existingFormData = json_decode($validated['existing_form_data'], true);
            // Extract student IDs from existing form data
            $existingStudentIds = array_column($existingFormData, 'student_id');
        }
        
        // Merge existing student IDs with new student IDs
        $allStudentIds = array_unique(array_merge($existingStudentIds, $validated['student_ids']));
        
        // Get all students (existing + new) with their classes
        $studentsQuery = \App\Models\Student::whereIn('id', $allStudentIds)
            ->with('schoolClass');
        
        // If homeroom teacher, restrict to their class only
        if (auth()->user()->isHomeroomTeacher()) {
            $studentsQuery->where('class_id', auth()->user()->assigned_class_id);
        }
        
        $students = $studentsQuery->get();
        
        if ($students->isEmpty()) {
            return redirect()->route('classes.index')
                ->with('error', 'Tidak ada siswa yang valid dipilih.');
        }
        
        // Get unique classes from selected students
        $classes = $students->pluck('schoolClass')->unique('id');
        
        $lateReasons = \App\Models\LateReason::active()->get();
        
        return view('late-attendance.bulk-review', compact('students', 'lateReasons', 'classes', 'existingFormData'));
    }
    
    /**
     * Store bulk late attendance records with automatic Telegram notification
     * Each student has individual data (time, reason, notes)
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'students' => 'required|array|min:1',
            'students.*.student_id' => 'required|exists:students,id',
            'students.*.class_id' => 'required|exists:classes,id',
            'students.*.late_date' => 'required|date',
            'students.*.arrival_time' => 'required',
            'students.*.late_reason_id' => 'required|exists:late_reasons,id',
            'students.*.notes' => 'nullable|string',
        ]);
        
        // Verify all students exist and belong to their specified classes
        $studentIds = array_column($validated['students'], 'student_id');
        $students = \App\Models\Student::whereIn('id', $studentIds)->get();
        
        if ($students->count() != count($studentIds)) {
            return back()->withErrors(['students' => 'Beberapa siswa tidak valid.'])->withInput();
        }
        
        // If homeroom teacher, verify they can only add students from their class
        if (auth()->user()->isHomeroomTeacher()) {
            foreach ($validated['students'] as $studentData) {
                if ($studentData['class_id'] != auth()->user()->assigned_class_id) {
                    abort(403, 'You can only record attendance for students from your assigned class.');
                }
            }
        }
        
        $createdRecords = [];
        $telegramService = new \App\Services\TelegramService();
        
        try {
            // Use transaction to ensure data consistency
            \DB::beginTransaction();
            
            // Create late attendance records for each student with their individual data
            foreach ($validated['students'] as $studentData) {
                $record = \App\Models\LateAttendance::create([
                    'student_id' => $studentData['student_id'],
                    'class_id' => $studentData['class_id'],
                    'late_reason_id' => $studentData['late_reason_id'],
                    'late_date' => $studentData['late_date'],
                    'arrival_time' => $studentData['arrival_time'],
                    'notes' => $studentData['notes'] ?? null,
                    'recorded_by' => auth()->id(),
                    'status' => 'pending',
                ]);
                
                $createdRecords[] = $record;
            }
            
            // Commit transaction first - only send Telegram if DB save succeeds
            \DB::commit();
            
            // Load relationships for Telegram notification
            $recordsWithRelations = \App\Models\LateAttendance::with(['student', 'schoolClass', 'lateReason', 'recordedBy'])
                ->whereIn('id', collect($createdRecords)->pluck('id'))
                ->get();
            
            // Send automatic Telegram notification for each record
            try {
                $telegramSent = $telegramService->sendBulkIndividualLateNotification($recordsWithRelations);
                
                // Update telegram_sent status for all records
                if ($telegramSent) {
                    \App\Models\LateAttendance::whereIn('id', collect($createdRecords)->pluck('id'))
                        ->update([
                            'telegram_sent' => true,
                            'telegram_sent_at' => now(),
                        ]);
                }
            } catch (\Exception $e) {
                // Log error but don't fail the request - records are already saved
                \Log::error('Telegram notification failed for bulk late attendance: ' . $e->getMessage());
            }
            
            $studentCount = count($createdRecords);
            
            // Redirect to classes index instead of specific class (since students may be from multiple classes)
            return redirect()->route('classes.index')
                ->with('success', "Berhasil mencatat keterlambatan untuk {$studentCount} siswa. Notifikasi Telegram dikirim otomatis.");
                
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Bulk late attendance store failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Gagal menyimpan data keterlambatan. Silakan coba lagi.'])->withInput();
        }
    }

    /**
     * Daily Late Attendance Report with filters, statistics and PDF export
     */
    public function dailyReport(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $classId = $request->get('class_id');
        $status = $request->get('status');
        $search = $request->get('search');
        $groupByClass = $request->boolean('group_by_class');

        // Build the query for late attendances
        $query = \App\Models\LateAttendance::with(['student', 'schoolClass', 'lateReason', 'recordedBy'])
            ->byDate($date);

        // Apply role-based restrictions
        if (auth()->user()->isHomeroomTeacher()) {
            $query->where('class_id', auth()->user()->assigned_class_id);
        }

        // Apply filters
        if ($classId) {
            $query->where('class_id', $classId);
        }

        if ($status) {
            if ($status === 'excused') {
                // Check for approved exit permissions for this date
                $query->whereHas('student', function($q) use ($date) {
                    $q->whereHas('exitPermissions', function($exitQ) use ($date) {
                        $exitQ->whereDate('exit_date', $date)
                              ->where('status', 'approved');
                    });
                });
            } else {
                $query->where('status', $status);
            }
        }

        if ($search) {
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Get the data
        $lateAttendances = $query->orderBy('arrival_time')->get();

        // Calculate summary statistics
        $totalLateStudents = $lateAttendances->count();
        $latePerClass = $lateAttendances->groupBy('class_id')->map->count();
        $mostCommonTimeRange = $this->getMostCommonTimeRange($lateAttendances);
        $totalExcused = $this->getExcusedCount($lateAttendances, $date);

        // Group by class if requested
        $groupedData = $groupByClass ? $lateAttendances->groupBy('schoolClass.name') : collect();

        // Get monthly statistics for insights
        $monthlyStats = $this->getMonthlyInsights($date);

        // Get available classes for filter
        $classes = auth()->user()->isHomeroomTeacher() 
            ? \App\Models\SchoolClass::where('id', auth()->user()->assigned_class_id)->get()
            : \App\Models\SchoolClass::active()->get();

        return view('late-attendance.daily-report', compact(
            'lateAttendances',
            'groupedData',
            'totalLateStudents',
            'latePerClass',
            'mostCommonTimeRange',
            'totalExcused',
            'monthlyStats',
            'classes',
            'date',
            'classId',
            'status',
            'search',
            'groupByClass'
        ));
    }

    /**
     * Export daily report as PDF
     */
    public function exportPDF(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $classId = $request->get('class_id');
        $type = $request->get('type', 'daily'); // daily, class, detailed

        // Build the query
        $query = \App\Models\LateAttendance::with(['student', 'schoolClass', 'lateReason', 'recordedBy'])
            ->byDate($date);

        // Apply role-based restrictions
        if (auth()->user()->isHomeroomTeacher()) {
            $query->where('class_id', auth()->user()->assigned_class_id);
        }

        if ($classId) {
            $query->where('class_id', $classId);
        }

        $lateAttendances = $query->orderBy('arrival_time')->get();

        // Calculate summary statistics
        $totalLateStudents = $lateAttendances->count();
        $latePerClass = $lateAttendances->groupBy('class_id')->map->count();
        $totalExcused = $this->getExcusedCount($lateAttendances, $date);

        // Group by class for class-specific reports
        $groupedData = $lateAttendances->groupBy('schoolClass.name');

        $data = [
            'date' => Carbon::parse($date)->format('d F Y'),
            'lateAttendances' => $lateAttendances,
            'groupedData' => $groupedData,
            'totalLateStudents' => $totalLateStudents,
            'latePerClass' => $latePerClass,
            'totalExcused' => $totalExcused,
            'type' => $type,
            'classId' => $classId,
            'className' => $classId ? \App\Models\SchoolClass::find($classId)?->name : null,
        ];

        $pdf = Pdf::loadView('late-attendance.pdf-report', $data);
        $filename = 'laporan-keterlambatan-' . $date . ($classId ? '-' . $data['className'] : '') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Get most common arrival time range
     */
    private function getMostCommonTimeRange($lateAttendances)
    {
        $timeRanges = [
            '07:00-07:30' => 0,
            '07:31-08:00' => 0,
            '08:01-08:30' => 0,
            '08:31+' => 0,
        ];

        foreach ($lateAttendances as $attendance) {
            $arrivalTime = Carbon::parse($attendance->arrival_time);
            $time = $arrivalTime->format('H:i');
            
            if ($time <= '07:30') {
                $timeRanges['07:00-07:30']++;
            } elseif ($time <= '08:00') {
                $timeRanges['07:31-08:00']++;
            } elseif ($time <= '08:30') {
                $timeRanges['08:01-08:30']++;
            } else {
                $timeRanges['08:31+']++;
            }
        }

        return collect($timeRanges)->sortDesc()->keys()->first() ?? 'Tidak ada data';
    }

    /**
     * Get count of excused students (with approved exit permissions)
     */
    private function getExcusedCount($lateAttendances, $date)
    {
        $excusedCount = 0;

        foreach ($lateAttendances as $attendance) {
            if ($attendance->student->hasApprovedExitPermission($date)) {
                $excusedCount++;
            }
        }

        return $excusedCount;
    }

    /**
     * Get monthly insights for statistics
     */
    private function getMonthlyInsights($date)
    {
        $currentMonth = Carbon::parse($date)->month;
        $currentYear = Carbon::parse($date)->year;

        // Get top 3 students with most lateness this month
        $topLateStudents = \App\Models\Student::withCount(['lateAttendances' => function($query) use ($currentMonth, $currentYear) {
                $query->byMonth($currentMonth, $currentYear);
            }])
            ->having('late_attendances_count', '>', 0)
            ->orderByDesc('late_attendances_count')
            ->take(3)
            ->get();

        // Get class with highest lateness frequency this month
        $classWithMostLate = \App\Models\SchoolClass::withCount(['lateAttendances' => function($query) use ($currentMonth, $currentYear) {
                $query->byMonth($currentMonth, $currentYear);
            }])
            ->having('late_attendances_count', '>', 0)
            ->orderByDesc('late_attendances_count')
            ->first();

        return [
            'topLateStudents' => $topLateStudents,
            'classWithMostLate' => $classWithMostLate,
        ];
    }
}
