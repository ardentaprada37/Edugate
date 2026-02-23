<x-app-layout>
    @push('styles')
    <style>
        /* Enhanced dropdown scroll containment styles */
        #student_dropdown_inner {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        /* Custom scrollbar */
        #student_dropdown_inner::-webkit-scrollbar {
            width: 6px;
        }
        #student_dropdown_inner::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        #student_dropdown_inner::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        #student_dropdown_inner::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Dropdown styling */
        #student_dropdown {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .student-option:hover {
            background-color: #f8fafc;
        }
    </style>
    @endpush

    <x-slot name="header">
        <div class="bg-custom-blue -mt-6 -mx-6 px-6 py-12 mb-6 shadow-md flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex-1 min-w-0">
                <h2 class="font-bold text-3xl text-white leading-tight drop-shadow-md">
                    Catat Siswa Multi
                </h2>
                <p class="text-white mt-2 text-sm opacity-90">Pilih beberapa siswa sekaligus untuk dicatat</p>
            </div>
            <a href="{{ route('late-attendance.index') }}" class="w-full sm:w-auto bg-white/10 hover:bg-white/20 text-white font-semibold py-2 px-4 rounded-full transition duration-300 inline-flex items-center justify-center backdrop-blur-sm text-sm border border-white/20">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Student Selection Section -->
            <div class="mb-8">
                <div class="mb-4">
                    <h3 class="text-xl font-bold text-gray-900 leading-none">Pilih Siswa</h3>
                    <p class="text-gray-500 text-sm mt-1">Cari dan pilih siswa yang datang terlambat</p>
                </div>
                
                <div class="relative w-full max-w-full">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center" style="color: rgba(22, 11, 106, 0.55);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="student_search" placeholder="Ketik nama siswa..."
                            class="late-attendance-input"
                            style="border-radius: 9999px; padding-left: 48px; height: 56px; font-weight: 600;"
                            autocomplete="off">
                    </div>
                    
                    <!-- Dropdown list -->
                    <div id="student_dropdown" class="hidden absolute z-50 w-full bg-white rounded-2xl shadow-2xl mt-3 overflow-hidden ring-1 ring-black ring-opacity-5 origin-top transform transition-all" style="border: 1px solid rgba(22, 11, 106, 0.12);">
                        <div id="student_dropdown_inner" class="overflow-y-auto" style="max-height: 50vh;">
                            @foreach($students as $student)
                                <div class="student-option px-5 md:px-6 py-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors duration-150 active:bg-blue-50" 
                                     data-id="{{ $student->id }}" 
                                     data-name="{{ $student->name }}" 
                                     data-class="{{ $student->schoolClass->name }}" 
                                     data-class-id="{{ $student->class_id }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0 pr-4">
                                            <p class="font-bold text-gray-900 truncate text-sm md:text-base">{{ $student->name }}</p>
                                            <p class="text-xs md:text-sm text-gray-500 truncate">{{ $student->schoolClass->name }}</p>
                                        </div>
                                        <div>
                                            <div class="bg-gray-100 p-1.5 md:p-2 rounded-lg">
                                                <svg class="w-4 h-4 md:w-5 md:h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selected Students List Form -->
            <form method="POST" action="{{ route('late-attendance.multi-store') }}" id="late_attendance_form">
                @csrf
                
                <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div class="min-w-0">
                        <h3 class="text-xl font-bold text-gray-900 leading-none">Daftar Terlambat</h3>
                        <p class="text-gray-500 text-sm mt-1">Detail siswa yang akan dicatat</p>
                    </div>
                    <span id="student_count_badge" class="bg-custom-blue text-white text-xs font-bold px-3 py-1 rounded-full inline-flex items-center h-fit shrink-0">0 Siswa</span>
                </div>
                
                <div id="selected_students_container" class="space-y-4">
                    <!-- Empty State -->
                    <div id="empty_state" class="text-center py-12 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                        <div class="bg-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-400 font-medium">Belum ada siswa yang dipilih</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div id="submit_section" class="mt-8 hidden">
                    <button type="submit" class="w-full bg-custom-blue hover:bg-opacity-90 text-white font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center text-lg">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Semua Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // State management
            let selectedStudents = [];
            let studentIdCounter = 0;

            // Get current date and time
            const today = new Date().toISOString().split('T')[0];
            const currentTime = new Date().toTimeString().slice(0, 5);

            // Late reasons data
            const lateReasons = @json($lateReasons);

            // Get elements
            const studentSearch = document.getElementById('student_search');
            const studentDropdown = document.getElementById('student_dropdown');
            const studentDropdownInner = document.getElementById('student_dropdown_inner');
            
            // Store original dropdown HTML
            const originalDropdownHTML = studentDropdownInner.innerHTML;

            // Show dropdown when search is focused
            studentSearch.addEventListener('focus', function() {
                studentDropdown.classList.remove('hidden');
                filterStudents(this.value);
            });

            // Search functionality
            studentSearch.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                filterStudents(searchTerm);
                studentDropdown.classList.remove('hidden');
            });

            // Filter students based on search term
            function filterStudents(searchTerm) {
                const search = searchTerm.toLowerCase();
                let hasVisibleOptions = false;
                
                // Restore original HTML if needed
                if (!studentDropdownInner.querySelector('.student-option')) {
                    studentDropdownInner.innerHTML = originalDropdownHTML;
                    attachStudentClickListeners();
                }
                
                const currentOptions = document.querySelectorAll('.student-option');
                
                currentOptions.forEach(option => {
                    const studentName = option.getAttribute('data-name').toLowerCase();
                    const className = option.getAttribute('data-class').toLowerCase();
                    const studentId = option.getAttribute('data-id');
                    
                    // Check if already selected
                    const isSelected = selectedStudents.some(s => s.id == studentId);
                    
                    if (isSelected) {
                        option.style.display = 'none';
                    } else if (studentName.includes(search) || className.includes(search)) {
                        option.style.display = '';
                        hasVisibleOptions = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                // Show "no results" message
                if (!hasVisibleOptions && search !== '') {
                    // Logic for no results view if desired
                }
            }

            // Attach click listeners
            function attachStudentClickListeners() {
                const currentOptions = document.querySelectorAll('.student-option');
                currentOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        const studentId = this.getAttribute('data-id');
                        const studentName = this.getAttribute('data-name');
                        const studentClass = this.getAttribute('data-class');
                        const studentClassId = this.getAttribute('data-class-id');

                        if (selectedStudents.some(s => s.id == studentId)) return;

                        selectedStudents.push({
                            id: studentId,
                            name: studentName,
                            class: studentClass,
                            classId: studentClassId,
                            uniqueId: studentIdCounter++
                        });

                        renderSelectedStudents();
                        
                        studentSearch.value = '';
                        studentDropdown.classList.add('hidden');
                        this.style.display = 'none';
                    });
                });
            }

            attachStudentClickListeners();

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!studentSearch.contains(event.target) && !studentDropdown.contains(event.target)) {
                    studentDropdown.classList.add('hidden');
                }
            });

            // Render selected students
            function renderSelectedStudents() {
                const container = document.getElementById('selected_students_container');
                const submitSection = document.getElementById('submit_section');
                const countBadge = document.getElementById('student_count_badge');

                if (selectedStudents.length === 0) {
                    container.innerHTML = `
                        <div id="empty_state" class="text-center py-12 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                            <div class="bg-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-400 font-medium">Belum ada siswa yang dipilih</p>
                        </div>
                    `;
                    submitSection.classList.add('hidden');
                    countBadge.textContent = '0 Siswa';
                    return;
                }

                submitSection.classList.remove('hidden');
                countBadge.textContent = `${selectedStudents.length} Siswa`;

                let html = '';
                selectedStudents.forEach((student, index) => {
                    html += `
                    <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 relative group animate-fade-in-up overflow-hidden">
                        <button type="button" onclick="removeStudent(${student.id})" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <div class="flex items-center mb-6 pr-8 min-w-0">
                            <div class="bg-card-gray w-12 h-12 rounded-full flex items-center justify-center text-gray-700 font-bold text-lg mr-4">
                                ${student.name.charAt(0).toUpperCase()}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-gray-900 text-lg leading-tight break-words">${student.name}</h4>
                                <p class="text-sm text-gray-500 break-words">${student.class}</p>
                            </div>
                        </div>

                        <input type="hidden" name="students[${index}][student_id]" value="${student.id}">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Late Date -->
                            <div>
                                <label class="block text-gray-500 text-xs font-bold mb-2 uppercase tracking-wide">Tanggal</label>
                                <input type="date" name="students[${index}][late_date]" value="${today}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-custom-blue focus:ring-2 focus:ring-blue-100 transition-all font-medium text-gray-700">
                            </div>

                            <!-- Arrival Time -->
                            <div>
                                <label class="block text-gray-500 text-xs font-bold mb-2 uppercase tracking-wide">Jam Datang</label>
                                <input type="time" name="students[${index}][arrival_time]" value="${currentTime}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-custom-blue focus:ring-2 focus:ring-blue-100 transition-all font-medium text-gray-700">
                            </div>

                            <!-- Late Reason -->
                            <div>
                                <label class="block text-gray-500 text-xs font-bold mb-2 uppercase tracking-wide">Alasan</label>
                                <select name="students[${index}][late_reason_id]" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:border-custom-blue focus:ring-2 focus:ring-blue-100 transition-all font-medium text-gray-700 cursor-pointer">
                                    <option value="">Pilih...</option>
                                    ${lateReasons.map(reason => `<option value="${reason.id}">${reason.reason}</option>`).join('')}
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <input type="text" name="students[${index}][notes]" placeholder="Catatan tambahan (opsional)..."
                                class="w-full px-0 py-2 bg-transparent border-0 border-b border-gray-200 focus:border-custom-blue focus:ring-0 text-sm text-gray-600 placeholder-gray-400 transition-colors">
                        </div>
                    </div>
                    `;
                });

                container.innerHTML = html;
            }

            // Remove student function
            window.removeStudent = function(studentId) {
                selectedStudents = selectedStudents.filter(s => s.id != studentId);
                renderSelectedStudents();
                
                const currentOptions = document.querySelectorAll('.student-option');
                currentOptions.forEach(option => {
                    if (option.getAttribute('data-id') == studentId) {
                        option.style.display = '';
                    }
                });
            };

            // Form validation
            document.getElementById('late_attendance_form').addEventListener('submit', function(e) {
                if (selectedStudents.length === 0) {
                    e.preventDefault();
                    // Simple toast or alert replacement could go here
                    alert('Silakan pilih minimal 1 siswa!');
                    return false;
                }
            });
        });
    </script>
    <style>
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 10px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
    </style>
    @endpush
</x-app-layout>
