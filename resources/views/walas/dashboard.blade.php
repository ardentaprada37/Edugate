<x-app-layout>
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
        
        <!-- Welcome Section -->
        <div class="bg-white rounded-3xl shadow-md p-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900 mb-2" style="font-family: 'Poppins', sans-serif;">Selamat Datang, Wali Kelas!</h1>
                <p class="text-sm text-gray-500 leading-relaxed" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Akun Anda terhubung ke kelas <span class="font-semibold text-gray-700">{{ $assignedClass?->name ?? '-' }}</span>. <br>
                    Anda hanya dapat melihat dan memproses izin keluar dari kelas ini.
                </p>
            </div>
            <div class="hidden md:block">
                <img src="{{ asset('images/din.png') }}" alt="Illustration" class="object-contain drop-shadow-2xl" style="width: 240px; height: 240px;">
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2" style="font-family: 'Poppins', sans-serif;">Ringkasan Kelas</h3>
            <p class="text-sm text-gray-600" style="font-family: 'Poppins', sans-serif;">
                Kelas: <span class="font-semibold text-gray-800">{{ $assignedClass?->name ?? '-' }}</span>
            </p>
            <p class="text-sm text-gray-600 mt-1" style="font-family: 'Poppins', sans-serif;">
                Permohonan izin keluar menunggu persetujuan: <span class="font-semibold text-red-600">{{ $pendingRequestsCount }}</span>
            </p>
        </div>

        <!-- Menu Cepat -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="header-primary px-6 py-4">
                <h3 class="text-white font-bold text-lg" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Menu cepat</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                    <!-- Izin Keluar -->
                    <a href="{{ route('exit-permissions.index') }}" class="card-primary rounded-2xl p-6 flex flex-col items-center justify-center text-center transition-all transform hover:scale-105 shadow-lg hover:opacity-90">
                        <div class="bg-purple-500/30 rounded-full p-3 mb-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <h4 class="text-white font-bold text-sm mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Izin Keluar</h4>
                        <p class="text-white/80 text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Kelola izin keluar siswa</p>
                    </a>

                    <!-- Kelola Data Siswa -->
                    <a href="{{ route('admin.students.index') }}" class="card-primary rounded-2xl p-6 flex flex-col items-center justify-center text-center transition-all transform hover:scale-105 shadow-lg hover:opacity-90">
                        <div class="bg-purple-500/30 rounded-full p-3 mb-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-white font-bold text-sm mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Kelola Data Siswa</h4>
                        <p class="text-white/80 text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Manajemen data siswa</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
