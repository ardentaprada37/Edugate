<x-app-layout>
    <x-slot name="header">
        <div class="late-attendance-hero -mt-6 -mx-6 px-6 py-8 mb-6 shadow-lg">
            <div class="max-w-7xl mx-auto late-attendance-hero-inner" style="position: relative;">
                <a href="{{ route('exit-permissions.index') }}" class="late-attendance-secondary-btn-nohover flex items-center" style="position: absolute; top: 0; right: 0;">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>

                <div class="text-center" style="padding-top: 10px; padding-bottom: 10px;">
                    <div class="mx-auto" style="width: 96px; height: 96px; display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset('images/form.png') }}" alt="Form" style="width: 100px; height: 100px; object-fit: contain; display: block;">
                    </div>
                    <h2 class="font-black text-3xl md:text-4xl text-white leading-tight drop-shadow-lg" style="margin-top: 18px; letter-spacing: 0.02em;">FORM IZIN KELUAR</h2>
                    <p class="late-attendance-hero-subtitle mt-3 text-sm md:text-base" style="font-weight: 700;">Lengkapi semua yang dibutuhkan</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen late-attendance-bg">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="late-attendance-card">
                <div class="late-attendance-card-body">
                    <form method="POST" action="{{ route('exit-permissions.store') }}" id="exitPermissionForm">
                        @csrf

                        <!-- Class Selection (if admin/teacher) -->
                        @if(!auth()->user()->isClassScopedRole())
                        <div class="mb-6">
                            <label for="class_id" class="block text-sm mb-2 late-attendance-label flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="#160B6A" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Kelas
                            </label>
                            <select id="class_id" name="class_id" class="late-attendance-input cursor-pointer" required>
                                <option value="">Pilih Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                        @endif

                        <!-- Student Selection -->
                        <div class="mb-6">
                            <label for="student_id" class="block text-sm mb-2 late-attendance-label flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="#160B6A" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Nama Siswa <span class="text-red-500">*</span>
                            </label>
                            <select id="student_id" name="student_id" class="late-attendance-input cursor-pointer" required>
                                <option value="">Pilih Siswa</option>
                                @if(auth()->user()->role === 'homeroom_teacher' || auth()->user()->role === 'walas')
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->student_number }})</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('student_id')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Permission Type -->
                        <div class="mb-6">
                            <label for="permission_type" class="block text-sm mb-2 late-attendance-label flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="#160B6A" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Jenis Izin <span class="text-red-500">*</span>
                            </label>
                            <select id="permission_type" name="permission_type" class="late-attendance-input cursor-pointer" required>
                                <option value="">Pilih Jenis Izin</option>
                                <option value="sick" {{ old('permission_type') == 'sick' ? 'selected' : '' }}>Sakit</option>
                                <option value="leave_early" {{ old('permission_type') == 'leave_early' ? 'selected' : '' }}>Izin Pulang Awal</option>
                                <option value="permission_out" {{ old('permission_type') == 'permission_out' ? 'selected' : '' }}>Izin Keluar</option>
                            </select>
                            @error('permission_type')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Exit Date -->
                        <div class="mb-6">
                            <label for="exit_date" class="block text-sm mb-2 late-attendance-label flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="#160B6A" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal Keluar <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="exit_date" name="exit_date" value="{{ old('exit_date', date('Y-m-d')) }}" class="late-attendance-input" required>
                            @error('exit_date')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Time Out -->
                        <div class="mb-6">
                            <label for="time_out" class="block text-sm mb-2 late-attendance-label flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="#160B6A" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Jam Keluar <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="time_out" name="time_out" value="{{ old('time_out', now()->format('H:i')) }}" class="late-attendance-input" required step="60">
                            @error('time_out')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Time In -->
                        <div class="mb-6" id="time_in_section">
                            <label for="time_in" class="block text-sm mb-2 late-attendance-label flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="#160B6A" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Jam Kembali <span class="text-gray-400">(Opsional - tergantung jenis izin)</span>
                            </label>
                            <input type="time" id="time_in" name="time_in" value="{{ old('time_in') }}" class="late-attendance-input" step="60">
                            @error('time_in')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Reason -->
                        <div class="mb-6">
                            <label for="reason" class="block text-sm mb-2 late-attendance-label flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="#160B6A" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                Alasan Keluar <span class="text-red-500">*</span>
                            </label>
                            <textarea id="reason" name="reason" rows="4" placeholder="Jelaskan alasan izin keluar..." class="late-attendance-input" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-8">
                            <label for="additional_notes" class="block text-sm mb-2 late-attendance-label flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="#160B6A" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Catatan Tambahan <span class="text-gray-400">(Opsional)</span>
                            </label>
                            <textarea id="additional_notes" name="additional_notes" rows="3" placeholder="Tambahkan catatan jika diperlukan..." class="late-attendance-input">{{ old('additional_notes') }}</textarea>
                            @error('additional_notes')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pt-6" style="border-top: 1px solid rgba(15, 23, 42, 0.10);">
                            <a href="{{ route('exit-permissions.index') }}" class="late-attendance-secondary-btn flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" class="late-attendance-primary-btn flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Ajukan Izin</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Permission type handling - show/hide Time In field based on permission type
        document.getElementById('permission_type').addEventListener('change', function() {
            const permissionType = this.value;
            const timeInSection = document.getElementById('time_in_section');
            const timeInInput = document.getElementById('time_in');
            const timeInLabel = timeInSection.querySelector('label span');
            
            if (permissionType === 'sick') {
                // For sick leave, Time In is not needed (staying home)
                timeInSection.style.display = 'none';
                timeInInput.required = false;
                timeInInput.value = ''; // Clear the value
            } else if (permissionType === 'leave_early') {
                // For leave early, Time In is not needed (going home)
                timeInSection.style.display = 'none';
                timeInInput.required = false;
                timeInInput.value = ''; // Clear the value
            } else if (permissionType === 'permission_out') {
                // For permission out, Time In is required (must return to class)
                timeInSection.style.display = 'block';
                timeInInput.required = true;
                timeInLabel.innerHTML = '<span class="text-red-500">* (Wajib diisi - waktu kembali ke kelas)</span>';
            } else {
                // Default state
                timeInSection.style.display = 'block';
                timeInInput.required = false;
                timeInLabel.textContent = '(Opsional - tergantung jenis izin)';
            }
        });

        // Trigger on page load if there's an old value
        document.addEventListener('DOMContentLoaded', function() {
            const permissionTypeSelect = document.getElementById('permission_type');
            if (permissionTypeSelect.value) {
                permissionTypeSelect.dispatchEvent(new Event('change'));
            }
        });


        @if(!auth()->user()->isClassScopedRole())
        // AJAX to load students when class is selected
        document.getElementById('class_id').addEventListener('change', function() {
            const classId = this.value;
            const studentSelect = document.getElementById('student_id');
            
            // Clear current options
            studentSelect.innerHTML = '<option value="">Select Student</option>';
            
            if (classId) {
                fetch(`{{ route('exit-permissions.students-by-class') }}?class_id=${classId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.textContent = `${student.name} (${student.student_number})`;
                            studentSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading students:', error));
            }
        });
        @endif
    </script>
</x-app-layout>
