<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Student Late History - {{ $student->name }}
            </h2>
            <a href="{{ route('classes.show', $student->class_id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Class
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Student Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Student Number</p>
                            <p class="text-lg font-medium text-gray-900">{{ $student->student_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Class</p>
                            <p class="text-lg font-medium text-gray-900">{{ $student->schoolClass->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Gender</p>
                            <p class="text-lg font-medium text-gray-900">{{ $student->gender }}</p>
                        </div>
                        @if($student->phone)
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="text-lg font-medium text-gray-900">{{ $student->phone }}</p>
                        </div>
                        @endif
                        @if($student->parent_phone)
                        <div>
                            <p class="text-sm text-gray-500">Parent Phone</p>
                            <p class="text-lg font-medium text-gray-900">{{ $student->parent_phone }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Late Status Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Late Attendance Status</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <p class="text-sm text-gray-500">Total Late Arrivals</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalLateCount }}</p>
                        </div>
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="text-2xl font-bold">
                                @if($lateStatus == 'parent_notification')
                                    <span class="text-red-600">⚠ Parent Notification Required</span>
                                @elseif($lateStatus == 'warning')
                                    <span class="text-yellow-600">⚠ Warning</span>
                                @else
                                    <span class="text-green-600">✓ Normal</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if($lateStatus == 'parent_notification')
                    <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-800">
                            <strong>Action Required:</strong> This student has been late 5 or more times. Parent notification is recommended.
                        </p>
                    </div>
                    @elseif($lateStatus == 'warning')
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <strong>Warning:</strong> This student has been late 3 or more times. Close monitoring is advised.
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Exit Permissions Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Exit Permissions</h3>
                        <a href="{{ route('exit-permissions.create') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            + Request Exit Permission
                        </a>
                    </div>
                    
                    @if($student->exitPermissions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exit Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($student->exitPermissions->sortByDesc('exit_date')->take(5) as $permission)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $permission->exit_date->format('d M Y') }}
                                            @if($permission->exit_time)
                                                <br><span class="text-xs text-gray-500">{{ $permission->exit_time->format('H:i') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($permission->reason, 40) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($permission->status === 'approved')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    ✓ Approved
                                                </span>
                                            @elseif($permission->status === 'rejected')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    ✗ Rejected
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    ⏳ Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('exit-permissions.show', $permission->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($student->exitPermissions->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('exit-permissions.index') }}?search={{ $student->name }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                    View all exit permissions →
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-sm">No exit permission requests found.</p>
                    @endif
                </div>
            </div>

            <!-- Late History Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Complete Late History</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrival Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recorded By</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($student->lateAttendances as $attendance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->late_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('H:i', strtotime($attendance->arrival_time)) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $attendance->lateReason->reason }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $attendance->notes ?: '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->recordedBy->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($attendance->status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($attendance->status == 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No late attendance records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
