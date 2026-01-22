<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-red-600 to-orange-600 -mt-6 -mx-6 px-6 py-8 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-3xl text-white leading-tight drop-shadow-lg flex items-center">
                        <svg class="w-10 h-10 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Form Pencatatan Keterlambatan
                    </h2>
                    <p class="text-red-100 mt-2">Isi data keterlambatan siswa dengan lengkap</p>
                </div>
                <a href="{{ route('classes.show', $student->class_id) }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-bold py-3 px-6 rounded-xl transition duration-300 flex items-center backdrop-blur-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-red-50 via-orange-50 to-yellow-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Box -->
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-3xl shadow-2xl p-6 mb-8 text-white transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-30 rounded-full p-4 mr-4">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold">⚠️ Siswa Terlambat Datang</p>
                        <p class="text-yellow-100 mt-1">Pastikan semua data sudah benar sebelum submit</p>
                    </div>
                </div>
            </div>
            
            <!-- Form Card -->
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-4 border-gradient">
                <div class="bg-gradient-to-r from-red-500 via-pink-500 to-purple-500 p-8 text-center">
                    <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-2xl p-4 inline-block mb-3">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black text-white drop-shadow-lg">Formulir Keterlambatan</h3>
                    <p class="text-red-100 mt-2">Isi data dengan lengkap dan akurat</p>
                </div>
                <div class="p-8 bg-gradient-to-br from-white to-gray-50">
                    
                    <form method="POST" action="{{ route('late-attendance.store') }}">
                        @csrf
                        
                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                        <input type="hidden" name="class_id" value="{{ $student->class_id }}">
                        
                        <!-- Student Info Card -->
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 mb-6 text-white">
                            <div class="flex items-center">
                                <div class="bg-white bg-opacity-30 rounded-2xl p-4 mr-4">
                                    <span class="text-3xl font-black">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm text-indigo-100">Nama Siswa</p>
                                    <p class="text-2xl font-bold">{{ $student->name }}</p>
                                    <p class="text-sm text-indigo-100 mt-1">Kelas: <span class="font-bold">{{ $student->schoolClass->name }}</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Exit Permission Check -->
                        @php
                            $exitPermission = $student->getExitPermissionForDate(date('Y-m-d'));
                        @endphp
                        @if($exitPermission)
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-6 mb-6 text-white">
                                <div class="flex items-center">
                                    <div class="bg-white bg-opacity-30 rounded-full p-3 mr-4">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xl font-bold mb-1">✓ Student Has Approved Exit Permission</p>
                                        <p class="text-sm text-green-100">Exit Date: {{ $exitPermission->exit_date->format('d M Y') }}</p>
                                        <p class="text-sm text-green-100">Reason: {{ Str::limit($exitPermission->reason, 60) }}</p>
                                        <a href="{{ route('exit-permissions.show', $exitPermission->id) }}" class="text-sm text-white underline hover:text-green-100 mt-2 inline-block">
                                            View exit permission details →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Late Date -->
                        <div class="mb-6">
                            <label for="late_date" class="block text-gray-800 text-lg font-bold mb-3 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tanggal Keterlambatan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="late_date" id="late_date" value="{{ old('late_date', date('Y-m-d')) }}" required
                                class="w-full px-6 py-4 text-lg border-3 border-gray-300 rounded-2xl focus:border-red-500 focus:ring-4 focus:ring-red-200 transition duration-300 @error('late_date') border-red-500 @enderror">
                            @error('late_date')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Arrival Time -->
                        <div class="mb-6">
                            <label for="arrival_time" class="block text-gray-800 text-lg font-bold mb-3 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Jam Kedatangan <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="arrival_time" id="arrival_time" value="{{ old('arrival_time', date('H:i')) }}" required
                                class="w-full px-6 py-4 text-lg border-3 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-200 transition duration-300 @error('arrival_time') border-red-500 @enderror">
                            @error('arrival_time')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Late Reason -->
                        <div class="mb-6">
                            <label for="late_reason_id" class="block text-gray-800 text-lg font-bold mb-3 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                Alasan Keterlambatan <span class="text-red-500">*</span>
                            </label>
                            <select name="late_reason_id" id="late_reason_id" required
                                class="w-full px-6 py-4 text-lg border-3 border-gray-300 rounded-2xl focus:border-yellow-500 focus:ring-4 focus:ring-yellow-200 transition duration-300 cursor-pointer @error('late_reason_id') border-red-500 @enderror">
                                <option value="">-- Pilih Alasan Keterlambatan --</option>
                                @foreach($lateReasons as $reason)
                                    <option value="{{ $reason->id }}" {{ old('late_reason_id') == $reason->id ? 'selected' : '' }}>
                                        {{ $reason->reason }}
                                    </option>
                                @endforeach
                            </select>
                            @error('late_reason_id')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-8">
                            <label for="notes" class="block text-gray-800 text-lg font-bold mb-3 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Catatan Tambahan <span class="text-gray-400">(Opsional)</span>
                            </label>
                            <textarea name="notes" id="notes" rows="4" placeholder="Tulis catatan tambahan jika diperlukan..."
                                class="w-full px-6 py-4 text-lg border-3 border-gray-300 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition duration-300 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm font-semibold mt-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between pt-6 border-t-4 border-gray-200">
                            <button type="submit" class="group relative bg-gradient-to-r from-red-500 via-pink-500 to-purple-500 hover:from-red-600 hover:via-pink-600 hover:to-purple-600 text-white font-black py-5 px-12 rounded-2xl text-xl shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center">
                                <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Simpan Catatan Keterlambatan</span>
                                <div class="absolute top-0 left-0 right-0 bottom-0 bg-white opacity-0 group-hover:opacity-20 rounded-2xl transition-opacity duration-300"></div>
                            </button>
                            <a href="{{ route('classes.show', $student->class_id) }}" class="text-gray-600 hover:text-gray-900 font-bold text-lg flex items-center group">
                                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
