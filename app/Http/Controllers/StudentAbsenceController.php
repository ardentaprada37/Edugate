<?php

namespace App\Http\Controllers;

use App\Models\StudentAbsence;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StudentAbsenceController extends Controller
{
    public function create(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $grade = $request->get('grade');
        $major = $request->get('major');

        $grades = SchoolClass::active()
            ->select('grade')
            ->distinct()
            ->orderBy('grade')
            ->pluck('grade');

        $majorsQuery = SchoolClass::active()
            ->select('major')
            ->distinct()
            ->orderBy('major');

        if ($grade) {
            $majorsQuery->where('grade', $grade);
        }

        $majors = $majorsQuery->pluck('major');

        $selectedClass = null;
        $students = collect();
        $existingAbsences = collect();

        if ($grade && $major) {
            $selectedClass = SchoolClass::active()
                ->where('grade', $grade)
                ->where('major', $major)
                ->first();

            if ($selectedClass) {
                $students = $selectedClass->students()->active()->orderBy('name')->get();
                $existingAbsences = StudentAbsence::with(['student'])
                    ->byDate($date)
                    ->where('class_id', $selectedClass->id)
                    ->get()
                    ->keyBy('student_id');
            }
        }

        return view('student-absences.create', compact(
            'date',
            'grade',
            'major',
            'grades',
            'majors',
            'selectedClass',
            'students',
            'existingAbsences'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'absence_date' => ['required', 'date'],
            'class_id' => ['required', 'exists:classes,id'],
            'statuses' => ['array'],
            'statuses.*' => ['nullable', 'in:S,I,A,T,D'],
        ]);

        $date = $validated['absence_date'];
        $class = SchoolClass::findOrFail($validated['class_id']);

        $studentIds = $class->students()->active()->pluck('id');
        $statuses = collect($validated['statuses'] ?? [])
            ->filter(function ($value) {
                return in_array($value, ['S', 'I', 'A', 'T', 'D'], true);
            });

        DB::transaction(function () use ($date, $class, $studentIds, $statuses) {
            StudentAbsence::where('class_id', $class->id)
                ->whereDate('absence_date', $date)
                ->whereIn('student_id', $studentIds)
                ->delete();

            $rows = [];
            foreach ($statuses as $studentId => $status) {
                $studentId = (int) $studentId;
                if (!$studentIds->contains($studentId)) {
                    continue;
                }

                $rows[] = [
                    'student_id' => $studentId,
                    'class_id' => $class->id,
                    'recorded_by' => auth()->id(),
                    'absence_date' => $date,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (count($rows) > 0) {
                StudentAbsence::insert($rows);
            }
        });

        return redirect()
            ->route('student-absences.create', [
                'date' => $date,
                'grade' => $class->grade,
                'major' => $class->major,
            ])
            ->with('success', 'Data ketidakhadiran berhasil disimpan.');
    }
}
