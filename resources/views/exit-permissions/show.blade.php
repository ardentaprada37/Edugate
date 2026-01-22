<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 -mt-6 -mx-6 px-6 py-8 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-3xl text-white leading-tight drop-shadow-lg flex items-center">
                        <svg class="w-10 h-10 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Detail Izin Keluar
                    </h2>
                    <p class="text-green-100 mt-2">Informasi lengkap permohonan izin keluar siswa</p>
                </div>
                <a href="{{ route('exit-permissions.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-bold py-3 px-6 rounded-xl transition duration-300 flex items-center backdrop-blur-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-green-50 via-teal-50 to-blue-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Main Details Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mb-6">
                <div class="bg-green-500 p-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Informasi Permohonan
                    </h3>
                </div>
                <div class="p-6 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Nama Siswa</p>
                            <p class="font-bold text-gray-900">{{ $exitPermission->student->name }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">NIS</p>
                            <p class="font-bold text-gray-900">{{ $exitPermission->student->student_number }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Kelas</p>
                            <p class="font-bold text-gray-900">{{ $exitPermission->schoolClass->name }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Tanggal Keluar</p>
                            <p class="font-bold text-gray-900">{{ $exitPermission->exit_date->format('d F Y') }}</p>
                        </div>
                        
                        @if($exitPermission->exit_time)
                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-600 font-semibold mb-1">Jam Keluar</p>
                            <p class="font-bold text-gray-900">{{ $exitPermission->exit_time->format('H:i') }} WIB</p>
                        </div>
                        @endif
                        
                        <div class="md:col-span-2 bg-gray-50 rounded-lg p-3 border border-gray-200">
                            <p class="text-xs text-gray-600 font-semibold mb-2">Status Keseluruhan</p>
                            @if($exitPermission->status === 'approved')
                                <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full bg-green-500 text-white">
                                    ✓ Disetujui
                                </span>
                            @elseif($exitPermission->status === 'rejected')
                                <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full bg-red-500 text-white">
                                    ✗ Ditolak
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm font-bold rounded-full bg-yellow-500 text-white">
                                    ⏳ Menunggu Persetujuan
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Reason Section -->
                    <div class="mt-4 bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                        <p class="text-xs text-yellow-700 font-semibold mb-2">Alasan Keluar</p>
                        <p class="text-gray-900 text-sm">{{ $exitPermission->reason }}</p>
                    </div>

                    @if($exitPermission->additional_notes)
                    <div class="mt-3 bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <p class="text-xs text-blue-700 font-semibold mb-2">Catatan Tambahan</p>
                        <p class="text-gray-900 text-sm">{{ $exitPermission->additional_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Homeroom Teacher Approval Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 mb-6">
                <div class="bg-purple-500 p-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Persetujuan Wali Kelas
                    </h3>
                </div>
                <div class="p-6 bg-white">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-gray-700 font-semibold">Status:</p>
                        <div>
                            @if($exitPermission->walas_status === 'approved')
                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full bg-green-500 text-white">
                                    ✓ Disetujui
                                </span>
                            @elseif($exitPermission->walas_status === 'rejected')
                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full bg-red-500 text-white">
                                    ✗ Ditolak
                                </span>
                            @else
                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full bg-yellow-500 text-white">
                                    ⏳ Menunggu
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($exitPermission->walas_approved_by)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-purple-50 rounded-xl p-4 border-2 border-purple-200">
                        <div>
                            <p class="text-sm text-purple-600 font-semibold mb-1">Disetujui Oleh</p>
                            <p class="font-bold text-gray-900">{{ $exitPermission->walasApprovedBy->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-purple-600 font-semibold mb-1">Waktu Persetujuan</p>
                            <p class="font-bold text-gray-900">{{ $exitPermission->walas_approved_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($exitPermission->walas_notes)
                    <div class="mt-4 bg-pink-50 rounded-xl p-4 border-2 border-pink-200">
                        <p class="text-sm text-pink-600 font-semibold mb-1">Catatan Wali Kelas</p>
                        <p class="text-gray-900">{{ $exitPermission->walas_notes }}</p>
                    </div>
                    @endif

                    @if(auth()->user()->isHomeroomTeacher() && auth()->user()->assigned_class_id == $exitPermission->class_id && $exitPermission->walas_status === 'pending')
                        <form method="POST" action="{{ route('exit-permissions.walas-approve', $exitPermission->id) }}" class="mt-6" onsubmit="return confirm('Apakah Anda yakin ingin mengirim keputusan ini?')">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label for="walas_notes" class="block text-gray-800 text-sm font-bold mb-2">Catatan (Opsional)</label>
                                <textarea id="walas_notes" name="walas_notes" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition duration-300" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit" name="action" value="approve" class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Setujui
                                </button>
                                <button type="submit" name="action" value="reject" class="flex-1 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Tolak
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Admin Approval Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <div class="bg-orange-500 p-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Persetujuan Admin
                    </h3>
                </div>
                <div class="p-6 bg-white">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-gray-700 font-semibold">Status:</p>
                        <div>
                            @if($exitPermission->admin_status === 'approved')
                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full bg-green-500 text-white">
                                    ✓ Disetujui
                                </span>
                            @elseif($exitPermission->admin_status === 'rejected')
                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full bg-red-500 text-white">
                                    ✗ Ditolak
                                </span>
                            @else
                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-bold rounded-full bg-yellow-500 text-white">
                                    ⏳ Menunggu
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($exitPermission->admin_approved_by)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-orange-50 rounded-xl p-4 border-2 border-orange-200">
                        <div>
                            <p class="text-sm text-orange-600 font-semibold mb-1">Disetujui Oleh</p>
                            <p class="font-bold text-gray-900">{{ $exitPermission->adminApprovedBy->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-orange-600 font-semibold mb-1">Waktu Persetujuan</p>
                            <p class="font-bold text-gray-900">{{ $exitPermission->admin_approved_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($exitPermission->admin_notes)
                    <div class="mt-4 bg-red-50 rounded-xl p-4 border-2 border-red-200">
                        <p class="text-sm text-red-600 font-semibold mb-1">Catatan Admin</p>
                        <p class="text-gray-900">{{ $exitPermission->admin_notes }}</p>
                    </div>
                    @endif

                    @if(auth()->user()->isAdmin() && $exitPermission->admin_status === 'pending')
                        <form method="POST" action="{{ route('exit-permissions.admin-approve', $exitPermission->id) }}" class="mt-6" onsubmit="return confirm('Apakah Anda yakin ingin mengirim keputusan ini?')">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <label for="admin_notes" class="block text-gray-800 text-sm font-bold mb-2">Catatan (Opsional)</label>
                                <textarea id="admin_notes" name="admin_notes" rows="3" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition duration-300" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit" name="action" value="approve" class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Setujui
                                </button>
                                <button type="submit" name="action" value="reject" class="flex-1 bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Tolak
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
