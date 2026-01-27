<x-app-layout>
    @push('styles')
    <style>
        /* Enhanced dropdown scroll containment styles */
        #student_dropdown_inner {
            /* Smooth scrolling behavior */
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: rgba(147, 51, 234, 0.5) rgba(243, 244, 246, 0.5);
        }

        /* Custom scrollbar for webkit browsers */
        #student_dropdown_inner::-webkit-scrollbar {
            width: 8px;
        }

        #student_dropdown_inner::-webkit-scrollbar-track {
            background: rgba(243, 244, 246, 0.5);
            border-radius: 10px;
        }

        #student_dropdown_inner::-webkit-scrollbar-thumb {
            background: rgba(147, 51, 234, 0.5);
            border-radius: 10px;
        }

        #student_dropdown_inner::-webkit-scrollbar-thumb:hover {
            background: rgba(147, 51, 234, 0.7);
        }

        /* Prevent text selection while scrolling */
        #student_dropdown_inner.scrolling {
            user-select: none;
        }

        /* Visual indicator when dropdown is active */
        #student_dropdown {
            box-shadow: 0 10px 25px rgba(147, 51, 234, 0.2);
        }

        /* Smooth transitions for student options */
        .student-option {
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        .student-option:hover {
            transform: translateX(4px);
        }

        .student-option:active {
            transform: translateX(2px);
        }
    </style>
    @endpush

    <x-slot name="header">
        <div class="bg-gradient-to-r from-red-600 to-orange-600 -mt-6 -mx-6 px-6 py-8 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-3xl text-white leading-tight drop-shadow-lg flex items-center">
                        <svg class="w-10 h-10 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Pencatatan Keterlambatan Multi-Siswa
                    </h2>
                    <p class="text-red-100 mt-2">Pilih beberapa siswa dan isi data keterlambatan untuk masing-masing siswa</p>
                </div>
                <a href="{{ route('late-attendance.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-bold py-3 px-6 rounded-xl transition duration-300 flex items-center backdrop-blur-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Box -->
            <div class="bg-gradient-to-r from-blue-400 to-indigo-500 rounded-3xl shadow-2xl p-6 mb-8 text-white transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-30 rounded-full p-4 mr-4">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold">💡 Cara Menggunakan Fitur Ini</p>
                        <p class="text-blue-100 mt-1">1. Pilih kelas (jika diperlukan) → 2. Cari & pilih siswa yang terlambat → 3. Isi detail per siswa → 4. Submit semua sekaligus</p>
                    </div>
                </div>
            </div>

            <!-- Student Selection Box -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-visible border-4 border-gradient mb-8">
                <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 p-6">
                    <h3 class="text-2xl font-black text-white drop-shadow-lg flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        1️⃣ Pilih Siswa yang Terlambat
                    </h3>
                    <p class="text-pink-100 mt-2">Cari dan pilih satu atau lebih siswa yang datang terlambat</p>
                </div>
                
                <div class="p-8 bg-gradient-to-br from-white to-gray-50">
                    <!-- Combined Search and Selection -->
                    <div class="mb-4">
                        <label for="student_search" class="block text-gray-800 text-lg font-bold mb-3 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari & Pilih Siswa
                        </label>
                        <div class="relative">
                            <input type="text" id="student_search" placeholder="Ketik nama siswa untuk mencari, lalu pilih dari daftar..." 
                                class="w-full px-6 py-4 text-lg border-3 border-gray-300 rounded-2xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition duration-300"
                                autocomplete="off">
                            
                            <!-- Dropdown list with enhanced scroll containment -->
                            <div id="student_dropdown" class="hidden absolute z-50 w-full bg-white border-3 border-purple-300 rounded-2xl shadow-2xl mt-2" style="max-height: 384px;">
                                <div id="student_dropdown_inner" class="overflow-y-auto overscroll-contain" style="max-height: 384px; scroll-behavior: smooth;">
                                    @foreach($students as $student)
                                        <div class="student-option px-6 py-4 hover:bg-purple-50 cursor-pointer border-b border-gray-100 transition duration-200" 
                                             data-id="{{ $student->id }}" 
                                             data-name="{{ $student->name }}" 
                                             data-class="{{ $student->schoolClass->name }}" 
                                             data-class-id="{{ $student->class_id }}">
                                            <div class="flex items-center space-x-3">
                                                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg p-2 w-10 h-10 flex items-center justify-center">
                                                    <span class="text-lg font-black text-white">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900">{{ $student->name }}</p>
                                                    <p class="text-sm text-gray-600">{{ $student->schoolClass->name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Klik pada siswa untuk menambahkannya ke daftar
                        </p>
                    </div>
                </div>
            </div>

            <!-- Selected Students List Form -->
            <form method="POST" action="{{ route('late-attendance.multi-store') }}" id="late_attendance_form">
                @csrf
                
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-4 border-gradient mb-8">
                    <div class="bg-gradient-to-r from-orange-500 via-red-500 to-pink-500 p-6">
                        <h3 class="text-2xl font-black text-white drop-shadow-lg flex items-center">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            2️⃣ Daftar Siswa Terlambat & Detail
                            <span id="student_count_badge" class="ml-3 bg-white bg-opacity-30 text-white text-lg font-bold px-4 py-1 rounded-full">0 Siswa</span>
                        </h3>
                        <p class="text-orange-100 mt-2">Isi detail keterlambatan untuk setiap siswa secara individual</p>
                    </div>
                    
                    <div class="p-8 bg-gradient-to-br from-white to-gray-50">
                        <div id="selected_students_container">
                            <!-- Empty State -->
                            <div id="empty_state" class="text-center py-16">
                                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="text-2xl font-bold text-gray-400 mb-2">Belum Ada Siswa Dipilih</h3>
                                <p class="text-gray-400">Pilih siswa dari kotak di atas untuk mulai mencatat keterlambatan</p>
                            </div>
                            
                            <!-- Selected students will be added here dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div id="submit_section" class="bg-white rounded-3xl shadow-2xl p-8 hidden">
                    <div class="flex items-center justify-between">
                        <button type="submit" class="group relative bg-gradient-to-r from-red-500 via-pink-500 to-purple-500 hover:from-red-600 hover:via-pink-600 hover:to-purple-600 text-white font-black py-5 px-12 rounded-2xl text-xl shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center">
                            <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Simpan Semua Catatan Keterlambatan</span>
                            <div class="absolute top-0 left-0 right-0 bottom-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                        </button>
                        <a href="{{ route('late-attendance.index') }}" class="text-gray-600 hover:text-gray-900 font-bold text-lg flex items-center group">
                            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Batal
                        </a>
                    </div>
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

            // Get current date and time in Indonesian format
            const today = new Date().toISOString().split('T')[0];
            const currentTime = new Date().toTimeString().slice(0, 5);

            // Late reasons data
            const lateReasons = @json($lateReasons);

            // Get elements
            const studentSearch = document.getElementById('student_search');
            const studentDropdown = document.getElementById('student_dropdown');
            const studentDropdownInner = document.getElementById('student_dropdown_inner');
            const studentOptions = document.querySelectorAll('.student-option');
            
            // Store original dropdown HTML (from inner container)
            const originalDropdownHTML = studentDropdownInner.innerHTML;

            // Prevent page scroll when dropdown is open and being scrolled
            let isDropdownOpen = false;
            
            studentDropdownInner.addEventListener('wheel', function(e) {
                // Check if we're at the top or bottom of the scroll
                const atTop = this.scrollTop === 0;
                const atBottom = this.scrollTop + this.clientHeight >= this.scrollHeight;
                
                // Prevent page scroll when we're scrolling inside the dropdown
                // Only allow default if we're at boundaries AND trying to scroll further
                if ((e.deltaY < 0 && !atTop) || (e.deltaY > 0 && !atBottom)) {
                    e.stopPropagation();
                }
            }, { passive: true });

            // Additional touch event handling for mobile devices
            studentDropdownInner.addEventListener('touchmove', function(e) {
                e.stopPropagation();
            }, { passive: true });

            // Show dropdown when search is focused
            studentSearch.addEventListener('focus', function() {
                studentDropdown.classList.remove('hidden');
                isDropdownOpen = true;
                filterStudents(this.value);
            });

            // Search functionality
            studentSearch.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                filterStudents(searchTerm);
                
                if (studentDropdown.classList.contains('hidden')) {
                    studentDropdown.classList.remove('hidden');
                }
            });

            // Filter students based on search term
            function filterStudents(searchTerm) {
                const search = searchTerm.toLowerCase();
                let hasVisibleOptions = false;
                
                // First, restore original HTML if it was replaced
                if (!studentDropdownInner.querySelector('.student-option')) {
                    studentDropdownInner.innerHTML = originalDropdownHTML;
                    // Re-attach event listeners to restored options
                    attachStudentClickListeners();
                }
                
                // Get fresh list of options
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

                // Reset scroll position to top when filtering
                studentDropdownInner.scrollTop = 0;

                // Show "no results" message if needed
                if (!hasVisibleOptions && search !== '') {
                    studentDropdownInner.innerHTML = '<div class="px-6 py-4 text-gray-500 text-center">Tidak ada siswa ditemukan</div>';
                }
            }

            // Attach click listeners to student options
            function attachStudentClickListeners() {
                const currentOptions = document.querySelectorAll('.student-option');
                currentOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        const studentId = this.getAttribute('data-id');
                        const studentName = this.getAttribute('data-name');
                        const studentClass = this.getAttribute('data-class');
                        const studentClassId = this.getAttribute('data-class-id');

                        // Check for duplicates
                        if (selectedStudents.some(s => s.id == studentId)) {
                            alert('Siswa ini sudah ada dalam daftar!');
                            return;
                        }

                        // Add to selected students array
                        selectedStudents.push({
                            id: studentId,
                            name: studentName,
                            class: studentClass,
                            classId: studentClassId,
                            uniqueId: studentIdCounter++
                        });

                        // Update UI
                        renderSelectedStudents();
                        
                        // Clear search and hide dropdown
                        studentSearch.value = '';
                        studentDropdown.classList.add('hidden');
                        isDropdownOpen = false;
                        
                        // Hide the selected student from dropdown
                        this.style.display = 'none';
                    });
                });
            }

            // Initial attachment of event listeners
            attachStudentClickListeners();

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!studentSearch.contains(event.target) && !studentDropdown.contains(event.target)) {
                    studentDropdown.classList.add('hidden');
                    isDropdownOpen = false;
                }
            });

            // Render selected students
            function renderSelectedStudents() {
                const container = document.getElementById('selected_students_container');
                const submitSection = document.getElementById('submit_section');
                const countBadge = document.getElementById('student_count_badge');

                if (selectedStudents.length === 0) {
                    // Show empty state
                    container.innerHTML = `
                        <div id="empty_state" class="text-center py-16">
                            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-400 mb-2">Belum Ada Siswa Dipilih</h3>
                            <p class="text-gray-400">Pilih siswa dari kotak di atas untuk mulai mencatat keterlambatan</p>
                        </div>
                    `;
                    if (submitSection) {
                        submitSection.classList.add('hidden');
                    }
                    if (countBadge) {
                        countBadge.textContent = '0 Siswa';
                    }
                    return;
                }

                // Show submit section and update badge
                if (submitSection) {
                    submitSection.classList.remove('hidden');
                }
                if (countBadge) {
                    countBadge.textContent = `${selectedStudents.length} Siswa`;
                }

                let html = '';
                selectedStudents.forEach((student, index) => {
                    html += `
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 mb-6 border-3 border-indigo-200 shadow-lg" data-student-id="${student.id}">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center flex-1">
                                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-4 mr-4 text-white">
                                    <span class="text-2xl font-black">${student.name.charAt(0).toUpperCase()}</span>
                                </div>
                                <div>
                                    <p class="text-sm text-indigo-600 font-semibold">Siswa #${index + 1}</p>
                                    <p class="text-2xl font-bold text-gray-800">${student.name}</p>
                                    <p class="text-sm text-gray-600 mt-1">Kelas: <span class="font-bold">${student.class}</span></p>
                                </div>
                            </div>
                            <button type="button" onclick="removeStudent(${student.id})" class="bg-red-500 hover:bg-red-600 text-white font-bold p-3 rounded-xl transition duration-300 flex items-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>

                        <input type="hidden" name="students[${index}][student_id]" value="${student.id}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Late Date -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="students[${index}][late_date]" value="${today}" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition duration-300">
                            </div>

                            <!-- Arrival Time -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Jam Kedatangan <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="students[${index}][arrival_time]" value="${currentTime}" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition duration-300">
                            </div>

                            <!-- Late Reason -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                    Alasan <span class="text-red-500">*</span>
                                </label>
                                <select name="students[${index}][late_reason_id]" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition duration-300 cursor-pointer">
                                    <option value="">-- Pilih Alasan --</option>
                                    ${lateReasons.map(reason => `<option value="${reason.id}">${reason.reason}</option>`).join('')}
                                </select>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Catatan (Opsional)
                                </label>
                                <textarea name="students[${index}][notes]" rows="2" placeholder="Catatan tambahan..."
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-300"></textarea>
                            </div>
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
                
                // Show the student in dropdown again
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
                    alert('Silakan pilih minimal 1 siswa!');
                    return false;
                }
            });
        });
    </script>
    @endpush
</x-app-layout>