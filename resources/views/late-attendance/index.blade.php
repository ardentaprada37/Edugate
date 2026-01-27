<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Late Attendance Reports
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
                    <form method="GET" action="{{ route('late-attendance.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Student</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                placeholder="Student name..." 
                                class="shadow-sm border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <!-- Class Filter -->
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                            <select name="class_id" id="class_id" class="shadow-sm border rounded w-full py-2 px-3 text-gray-700">
                                <option value="">All Classes</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Filter -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="date" id="date" value="{{ request('date') }}" 
                                class="shadow-sm border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <!-- Buttons -->
                        <div class="md:col-span-3 flex gap-2">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Apply Filters
                            </button>
                            <a href="{{ route('late-attendance.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Late Attendance Records</h3>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('late-attendance.multi-create') }}" class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Catat Multi-Siswa
                            </a>
                            <div class="text-sm text-gray-600">
                                Total: {{ $lateAttendances->total() }} records
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrival Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recorded By</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($lateAttendances as $attendance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->late_date->format('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <a href="{{ route('students.show', $attendance->student_id) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $attendance->student->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->schoolClass->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ date('H:i', strtotime($attendance->arrival_time)) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $attendance->lateReason->reason }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->recordedBy->name ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No late attendance records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $lateAttendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh halaman setiap 30 detik untuk update data real-time
        setInterval(function() {
            // Hanya refresh jika tidak ada form yang sedang di-submit atau input yang aktif
            if (!document.querySelector('form:target') && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'SELECT') {
                // Simpan scroll position
                const scrollPosition = window.scrollY;
                
                // Refresh halaman
                window.location.reload();
                
                // Restore scroll position setelah reload
                setTimeout(() => {
                    window.scrollTo(0, scrollPosition);
                }, 100);
            }
        }, 30000); // 30 detik

        // Tambahkan visual indicator untuk auto-refresh
        let countdown = 30;
        const updateCountdown = () => {
            const indicator = document.getElementById('refresh-indicator');
            if (indicator) {
                indicator.textContent = `Auto-refresh dalam ${countdown}s`;
                countdown--;
                if (countdown < 0) countdown = 30;
            }
        };

        // Buat indicator elemen
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.max-w-7xl .flex.justify-between');
            if (header) {
                const indicator = document.createElement('div');
                indicator.id = 'refresh-indicator';
                indicator.className = 'text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded';
                indicator.textContent = 'Auto-refresh dalam 30s';
                header.appendChild(indicator);
                
                // Update countdown setiap detik
                setInterval(updateCountdown, 1000);
            }
        });

        // Pause auto-refresh ketika user sedang mengisi form
        let pauseRefresh = false;
        document.addEventListener('focusin', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') {
                pauseRefresh = true;
            }
        });

        document.addEventListener('focusout', function(e) {
            setTimeout(() => {
                pauseRefresh = false;
            }, 1000);
        });
    </script>
</x-app-layout>
