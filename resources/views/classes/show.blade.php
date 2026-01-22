<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 -mt-6 -mx-6 px-6 py-8 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-3xl text-white leading-tight drop-shadow-lg flex items-center">
                        <svg class="w-10 h-10 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ $class->name }}
                    </h2>
                    <p class="text-indigo-100 mt-2">{{ $class->description }} • {{ $class->students->count() }} Siswa</p>
                </div>
                <a href="{{ route('classes.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-bold py-3 px-6 rounded-xl transition duration-300 flex items-center backdrop-blur-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-indigo-100">
                <div class="bg-gradient-to-r from-red-500 to-pink-500 p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-white flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Pilih Siswa yang Telat
                            </h3>
                            <p class="text-red-100 mt-1">Centang siswa yang terlambat, lalu klik "Submit Selection"</p>
                        </div>
                        <div id="bulkActionButtons" class="hidden">
                            <button type="button" onclick="submitBulkSelection()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-bold py-3 px-6 rounded-xl transition duration-300 flex items-center backdrop-blur-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Submit Selection (<span id="selectedCount">0</span>)
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <form id="bulkSelectionForm" action="{{ route('late-attendance.bulk-review') }}" method="POST">
                        @csrf
                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                        <input type="hidden" name="existing_form_data" id="existingFormDataInput" value="">
                        
                        <div class="space-y-3">
                            @forelse($class->students as $student)
                            <div class="group bg-gradient-to-r from-white to-gray-50 hover:from-indigo-50 hover:to-purple-50 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border-2 border-gray-100 hover:border-indigo-300" data-student-card>
                                <div class="flex items-center justify-between">
                                    <!-- Checkbox -->
                                    <div class="flex items-center mr-4">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" 
                                            class="w-6 h-6 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 focus:ring-2 cursor-pointer student-checkbox"
                                            onchange="updateBulkActions()">
                                    </div>
                                    
                                    <!-- Student Info -->
                                    <div class="flex items-center space-x-6 flex-1">
                                        <!-- Avatar -->
                                        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-4 w-16 h-16 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                            <span class="text-2xl font-black text-white">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                        </div>
                                        
                                        <!-- Details -->
                                        <div>
                                            <div class="flex items-center space-x-3 mb-1">
                                                <h4 class="text-xl font-bold text-gray-900">{{ $student->name }}</h4>
                                                @if($student->hasApprovedExitPermission(now()->format('Y-m-d')))
                                                    <span class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full" title="Has approved exit permission today">
                                                        ✓ Izin Keluar
                                                    </span>
                                                @endif
                                                @if($student->lateAttendances->count() >= 5)
                                                    <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full animate-pulse">
                                                        🚨 {{ $student->lateAttendances->count() }}x
                                                    </span>
                                                @elseif($student->lateAttendances->count() >= 3)
                                                    <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">
                                                        ⚠️ {{ $student->lateAttendances->count() }}x
                                                    </span>
                                                @elseif($student->lateAttendances->count() > 0)
                                                    <span class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded-full">
                                                        {{ $student->lateAttendances->count() }}x
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                    {{ $student->student_number }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    {{ $student->gender }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('late-attendance.create', $student->id) }}" class="group/btn bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Catat Telat
                                        </a>
                                        <a href="{{ route('students.show', $student->id) }}" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Riwayat
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-20">
                                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-gray-500 text-xl">Tidak ada siswa di kelas ini</p>
                            </div>
                            @endforelse
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Restore selected checkboxes from sessionStorage when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const savedSelections = JSON.parse(sessionStorage.getItem('selectedStudents') || '[]');
            
            savedSelections.forEach(studentId => {
                const checkbox = document.querySelector(`.student-checkbox[value="${studentId}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
            
            updateBulkActions();
        });

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            const count = checkboxes.length;
            const bulkButtons = document.getElementById('bulkActionButtons');
            const selectedCount = document.getElementById('selectedCount');
            
            // Save selected student IDs to sessionStorage
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);
            sessionStorage.setItem('selectedStudents', JSON.stringify(selectedIds));
            
            if (count > 0) {
                bulkButtons.classList.remove('hidden');
                selectedCount.textContent = count;
            } else {
                bulkButtons.classList.add('hidden');
            }
        }

        function submitBulkSelection() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Silakan pilih minimal satu siswa');
                return;
            }
            
            // Get existing form data from sessionStorage (if user is adding more students)
            const existingFormData = sessionStorage.getItem('bulkLateFormData');
            if (existingFormData) {
                document.getElementById('existingFormDataInput').value = existingFormData;
                // Clear bulkLateFormData after sending it
                sessionStorage.removeItem('bulkLateFormData');
            }
            
            // Clear selectedStudents will happen after form is submitted and we're in bulk-review page
            sessionStorage.removeItem('selectedStudents');
            document.getElementById('bulkSelectionForm').submit();
        }
    </script>
</x-app-layout>
