<x-app-layout>
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
        
        <!-- Welcome Section -->
        <div class="bg-white rounded-3xl shadow-md p-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900 mb-2" style="font-family: 'Poppins', sans-serif;">Selamat Datang, Admin!</h1>
                <p class="text-sm text-gray-500 leading-relaxed" style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                    Let's start recording students who arrive late today.<br>
                    All data is saved neatly and easy to check anytime
                </p>
            </div>
            <div class="hidden md:block">
                <img src="{{ asset('build/assets/din.png') }}" alt="Illustration" class="object-contain drop-shadow-2xl" style="width: 240px; height: 240px;">
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Telat Hari Ini -->
            <div class="card-primary rounded-2xl shadow-lg p-6 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-white text-xs font-bold uppercase tracking-wider mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">TELAT HARI INI</div>
                        <div class="text-white font-black mb-1" style="font-family: 'Poppins', sans-serif; font-size: 4rem; line-height: 1;">{{ $stats['total_late_today'] }}</div>
                        <div class="text-white text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Siswa terlambat</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-3">
                        <img src="{{ asset('build/assets/jam.png') }}" alt="Clock" class="w-16 h-16 object-contain"  style="width: 100px; height: 100px;">
                    </div>
                </div>
            </div>

            <!-- Card 2: Telat Bulan Ini -->
            <div class="card-primary rounded-2xl shadow-lg p-6 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-white text-xs font-bold uppercase tracking-wider mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">TELAT BULAN INI</div>
                        <div class="text-white font-black mb-1" style="font-family: 'Poppins', sans-serif; font-size: 4rem; line-height: 1;">{{ $stats['total_late_this_month'] }}</div>
                        <div class="text-white text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Siswa terlambat</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-3">
                        <img src="{{ asset('build/assets/stat.png') }}" alt="Stats" class="w-16 h-16 object-contain" style="width: 100px; height: 100px;">
                    </div>
                </div>
            </div>

            <!-- Card 3: Menunggu Approval -->
            @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
            <div class="card-primary rounded-2xl shadow-lg p-6 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-white text-xs font-bold uppercase tracking-wider mb-2" style="font-family: 'Poppins', sans-serif; font-weight: 700;">MENUNGGU APPROVAL</div>
                        <div class="text-white font-black mb-1" style="font-family: 'Poppins', sans-serif; font-size: 4rem; line-height: 1;">{{ $stats['pending_count'] }}</div>
                        <div class="text-white text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Izin keluar perlu approval</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-3">
                        <img src="{{ asset('build/assets/alert.png') }}" alt="Alert" class="w-16 h-16 object-contain" style="width: 130px; height: 100px;">
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Menu Cepat -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="header-primary px-6 py-4">
                <h3 class="text-white font-bold text-lg" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Menu cepat</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('classes.index') }}" class="card-primary rounded-2xl p-8 flex flex-col items-center justify-center text-center transition-all transform hover:scale-105 shadow-lg hover:opacity-90">
                        <div class="bg-purple-500/30 rounded-full p-4 mb-4">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                        </div>
                        <h4 class="text-white font-bold text-base mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Catat Siswa Telat</h4>
                        <p class="text-white/80 text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Rekam keterlambatan</p>
                    </a>

                    <a href="{{ route('late-attendance.index') }}" class="card-primary rounded-2xl p-8 flex flex-col items-center justify-center text-center transition-all transform hover:scale-105 shadow-lg hover:opacity-90">
                        <div class="bg-purple-500/30 rounded-full p-4 mb-4">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
                        </div>
                        <h4 class="text-white font-bold text-base mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Laporan Keterlambatan</h4>
                        <p class="text-white/80 text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Lihat data & statistik</p>
                    </a>

                    @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.students.index') }}" class="card-primary rounded-2xl p-8 flex flex-col items-center justify-center text-center transition-all transform hover:scale-105 shadow-lg hover:opacity-90">
                        <div class="bg-purple-500/30 rounded-full p-4 mb-4">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                        </div>
                        <h4 class="text-white font-bold text-base mb-1" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Kelola Data Siswa</h4>
                        <p class="text-white/80 text-xs" style="font-family: 'Poppins', sans-serif; font-weight: 400;">Admin panel</p>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top 5 Siswa Sering Telat -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="header-primary px-6 py-4">
                <h3 class="text-white font-bold text-lg" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Top 5 Siswa Sering Telat</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">NAMA SISWA</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">KELAS</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">JUMLAH</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">STATUS</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($stats['top_late_students'] as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 uppercase">{{ $student->schoolClass->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $student->late_attendances_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->late_attendances_count >= 5)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">Warning</span>
                                @elseif($student->late_attendances_count >= 3)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Warning</span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Normal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('students.show', $student->id) }}" class="text-gray-500 hover:text-[#231591] font-medium transition">View Details</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No late attendance records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Classes with Most Late Arrivals -->
        @if(auth()->user()->isAdmin() || auth()->user()->isTeacher())
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="header-primary px-6 py-4">
                <h3 class="text-white font-bold text-lg" style="font-family: 'Poppins', sans-serif; font-weight: 700;">Classes with Most Late Arrivals</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">CLASS NAME</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">TOTAL LATE</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($stats['classes_with_most_late'] as $class)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 uppercase">{{ $class->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $class->late_attendances_count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-8 text-center text-sm text-gray-500">No late attendance records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

</x-app-layout>
