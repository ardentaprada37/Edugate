<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExitPermission;
use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExitPermissionController extends Controller
{
    // Display all exit permissions (with filters)
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ExitPermission::with(['student', 'schoolClass', 'submittedBy', 'walasApprovedBy', 'adminApprovedBy']);

        // Role-based filtering
        if ($user->role === 'homeroom_teacher') {
            $query->where('class_id', $user->assigned_class_id);
        }

        // Apply filters
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('exit_date', $request->date);
        }

        if ($request->filled('search')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $exitPermissions = $query->latest()->paginate(15);
        $classes = SchoolClass::all();

        // Load classes with pending exit permissions count
        if ($user->role === 'homeroom_teacher') {
            $classesWithCount = SchoolClass::where('id', $user->assigned_class_id)->get();
        } else {
            $classesWithCount = SchoolClass::active()->get();
        }
        
        // Load pending exit permissions count for each class
        $classesWithCount->load(['exitPermissions' => function($query) use ($user) {
            // For homeroom teacher, show pending walas approvals
            if ($user->role === 'homeroom_teacher') {
                $query->where('walas_status', 'pending');
            } else {
                // For admin/teacher, show all pending
                $query->where('status', 'pending');
            }
        }]);

        return view('exit-permissions.index', compact('exitPermissions', 'classes', 'classesWithCount'));
    }

    // Show form to create new exit permission
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role === 'homeroom_teacher') {
            $classes = SchoolClass::where('id', $user->assigned_class_id)->get();
            $students = Student::where('class_id', $user->assigned_class_id)->active()->get();
        } else {
            $classes = SchoolClass::all();
            $students = Student::active()->get();
        }

        return view('exit-permissions.create', compact('classes', 'students'));
    }

    // Store new exit permission
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'exit_date' => 'required|date',
            'exit_time' => 'nullable|date_format:H:i',
            'reason' => 'required|string|max:1000',
            'additional_notes' => 'nullable|string|max:1000',
        ]);

        $student = Student::findOrFail($validated['student_id']);

        $exitPermission = ExitPermission::create([
            'student_id' => $validated['student_id'],
            'class_id' => $student->class_id,
            'submitted_by' => Auth::id(),
            'exit_date' => $validated['exit_date'],
            'exit_time' => $validated['exit_time'],
            'reason' => $validated['reason'],
            'additional_notes' => $validated['additional_notes'],
        ]);

        return redirect()->route('exit-permissions.index')
            ->with('success', 'Exit permission submitted successfully!');
    }

    // Show single exit permission details
    public function show(ExitPermission $exitPermission)
    {
        $user = Auth::user();

        // Check if homeroom teacher can only see their class
        if ($user->role === 'homeroom_teacher' && $exitPermission->class_id !== $user->assigned_class_id) {
            abort(403, 'Unauthorized access');
        }

        $exitPermission->load(['student', 'schoolClass', 'submittedBy', 'walasApprovedBy', 'adminApprovedBy']);

        return view('exit-permissions.show', compact('exitPermission'));
    }

    // Walas approval/rejection
    public function walasApprove(Request $request, ExitPermission $exitPermission)
    {
        $user = Auth::user();

        // Check if user is homeroom teacher of this class
        if ($user->role !== 'homeroom_teacher' || $exitPermission->class_id !== $user->assigned_class_id) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        $exitPermission->update([
            'walas_status' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
            'walas_approved_by' => $user->id,
            'walas_approved_at' => now(),
            'walas_notes' => $validated['notes'],
        ]);

        $exitPermission->updateOverallStatus();

        return redirect()->back()->with('success', 'Exit permission ' . $validated['action'] . 'd successfully!');
    }

    // Admin approval/rejection
    public function adminApprove(Request $request, ExitPermission $exitPermission)
    {
        $user = Auth::user();

        // Check if user is admin or teacher
        if (!in_array($user->role, ['admin', 'teacher'])) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500',
        ]);

        $exitPermission->update([
            'admin_status' => $validated['action'] === 'approve' ? 'approved' : 'rejected',
            'admin_approved_by' => $user->id,
            'admin_approved_at' => now(),
            'admin_notes' => $validated['notes'],
        ]);

        $exitPermission->updateOverallStatus();

        return redirect()->back()->with('success', 'Exit permission ' . $validated['action'] . 'd successfully!');
    }

    // Get students by class (AJAX)
    public function getStudentsByClass(Request $request)
    {
        $students = Student::where('class_id', $request->class_id)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name', 'student_number']);

        return response()->json($students);
    }

    // Show list of classes with pending exit permissions count
    public function classList()
    {
        $user = Auth::user();
        
        // Get classes based on role
        if ($user->role === 'homeroom_teacher') {
            $classes = SchoolClass::where('id', $user->assigned_class_id)->get();
        } else {
            $classes = SchoolClass::active()->get();
        }
        
        // Load pending exit permissions count for each class
        $classes->load(['exitPermissions' => function($query) use ($user) {
            // For homeroom teacher, show pending walas approvals
            if ($user->role === 'homeroom_teacher') {
                $query->where('walas_status', 'pending');
            } else {
                // For admin/teacher, show all pending
                $query->where('status', 'pending');
            }
        }]);
        
        return view('exit-permissions.classes', compact('classes'));
    }

    // Show exit permissions for specific class
    public function showClassExitPermissions($classId)
    {
        $user = Auth::user();
        $class = SchoolClass::findOrFail($classId);
        
        // Check authorization for homeroom teacher
        if ($user->role === 'homeroom_teacher' && $class->id !== $user->assigned_class_id) {
            abort(403, 'You can only access exit permissions for your assigned class.');
        }
        
        // Get exit permissions for this class
        $query = ExitPermission::where('class_id', $class->id)
            ->with(['student', 'submittedBy', 'walasApprovedBy', 'adminApprovedBy']);
        
        // Filter based on role
        if ($user->role === 'homeroom_teacher') {
            // Show pending walas approvals for homeroom teacher
            $query->where('walas_status', 'pending');
        }
        
        $exitPermissions = $query->latest()->get();
        
        return view('exit-permissions.class-show', compact('class', 'exitPermissions'));
    }
}
