<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExitPermission;
use App\Models\SchoolClass;

class WalasController extends Controller
{
    /**
     * Display the Walas dashboard with feature cards
     */
    public function dashboard()
    {
        $user = auth()->user();

        if (!$user->hasAssignedClass()) {
            abort(403, 'Akun walas belum terhubung ke kelas. Hubungi admin.');
        }

        $assignedClass = SchoolClass::find($user->assigned_class_id);
        $pendingRequestsCount = ExitPermission::where('class_id', $user->assigned_class_id)
            ->where('walas_status', 'pending')
            ->count();

        return view('walas.dashboard', compact('assignedClass', 'pendingRequestsCount'));
    }
}
