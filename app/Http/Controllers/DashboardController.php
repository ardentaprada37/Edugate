<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get statistics based on role
        if ($user->isAdmin() || $user->isTeacher()) {
            $stats = [
                'total_late_today' => \App\Models\LateAttendance::whereDate('late_date', today())->count(),
                'total_late_this_month' => \App\Models\LateAttendance::whereMonth('late_date', now()->month)
                    ->whereYear('late_date', now()->year)->count(),
                'pending_count' => \App\Models\ExitPermission::pending()->count(),
                'top_late_students' => \App\Models\Student::withCount('lateAttendances')
                    ->orderBy('late_attendances_count', 'desc')
                    ->take(5)
                    ->get(),
                'classes_with_most_late' => \App\Models\SchoolClass::withCount('lateAttendances')
                    ->orderBy('late_attendances_count', 'desc')
                    ->take(5)
                    ->get(),
                'exit_permissions_pending' => \App\Models\ExitPermission::pending()->count(),
                'exit_permissions_today' => \App\Models\ExitPermission::whereDate('exit_date', today())->approved()->count(),
            ];
        } else {
            // Homeroom teacher - only their class
            $stats = [
                'total_late_today' => \App\Models\LateAttendance::where('class_id', $user->assigned_class_id)
                    ->whereDate('late_date', today())->count(),
                'total_late_this_month' => \App\Models\LateAttendance::where('class_id', $user->assigned_class_id)
                    ->whereMonth('late_date', now()->month)
                    ->whereYear('late_date', now()->year)->count(),
                'pending_count' => \App\Models\ExitPermission::where('class_id', $user->assigned_class_id)->where('walas_status', 'pending')->count(),
                'top_late_students' => \App\Models\Student::where('class_id', $user->assigned_class_id)
                    ->withCount('lateAttendances')
                    ->orderBy('late_attendances_count', 'desc')
                    ->take(5)
                    ->get(),
                'exit_permissions_pending' => \App\Models\ExitPermission::where('class_id', $user->assigned_class_id)->pending()->count(),
                'exit_permissions_today' => \App\Models\ExitPermission::where('class_id', $user->assigned_class_id)->whereDate('exit_date', today())->approved()->count(),
            ];
        }
        
        return view('dashboard', compact('stats'));
    }
}
