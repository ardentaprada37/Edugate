<x-app-layout>
    <x-slot name="header">
        <div class="exit-permissions-card-header exit-permissions-page-hero -mt-6 -mx-6 px-6 py-8 mb-6 shadow-lg">
            <div class="max-w-7xl mx-auto flex justify-between items-center exit-permissions-page-hero-inner">
                <div>
                    <h2 class="font-bold text-3xl md:text-4xl text-white leading-tight flex items-center exit-permissions-page-title">
                        <svg class="w-10 h-10 md:w-12 md:h-12 mr-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Daftar Izin Keluar
                    </h2>
                    <p class="exit-permissions-subtitle mt-1 text-sm md:text-base">Kelola dan pantau permohonan izin keluar siswa</p>
                </div>
                <a href="{{ route('exit-permissions.create') }}" class="exit-permissions-header-btn font-semibold py-3 px-6 md:px-8 rounded-xl transition duration-300 flex items-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Izin Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen exit-permissions-bg exit-permissions-page">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-500 text-white px-6 py-4 rounded-2xl shadow-lg flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="exit-permissions-card" x-data="{ showFilters: true }">
                <div class="px-6 py-4 flex items-center justify-between cursor-pointer select-none bg-gray-50" @click="showFilters = !showFilters">
                    <h3 class="text-base md:text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-custom-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter Pencarian
                        </h3>
                        <svg class="w-5 h-5 text-gray-500 transition-transform duration-300" :class="{'rotate-180': showFilters}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                </div>
                <div class="px-6 pb-6 pt-2" x-show="showFilters" x-transition>
                    <form method="GET" action="{{ route('exit-permissions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                        <div>
                            <label for="search" class="block text-sm mb-2 exit-permissions-label">Cari Siswa</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama siswa..." class="exit-permissions-input text-sm">
                        </div>

                        @if(auth()->user()->role !== 'homeroom_teacher')
                        <div>
                            <label for="class_id" class="block text-sm mb-2 exit-permissions-label">Kelas</label>
                            <select name="class_id" id="class_id" class="exit-permissions-input text-sm">
                                <option value="">Semua Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label for="status" class="block text-sm mb-2 exit-permissions-label">Status</label>
                            <select name="status" id="status" class="exit-permissions-input text-sm">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div>
                            <label for="date" class="block text-sm mb-2 exit-permissions-label">Tanggal Keluar</label>
                            <input type="date" name="date" id="date" value="{{ request('date') }}" class="exit-permissions-input text-sm">
                        </div>

                        <div class="col-span-full flex flex-wrap gap-3 items-center mt-2">
                            <button type="submit" class="exit-permissions-primary-btn font-semibold px-8 py-3 rounded-xl shadow-md transition-all duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Terapkan
                            </button>
                            <a href="{{ route('exit-permissions.index') }}" class="exit-permissions-secondary-btn font-semibold px-8 py-3 rounded-xl shadow-sm transition-all duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="exit-permissions-card" x-data="{ showTable: true }">
                <div class="px-6 py-4 exit-permissions-card-header cursor-pointer" @click="showTable = !showTable">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg md:text-2xl font-bold text-white flex items-center">
                            <svg class="w-7 h-7 md:w-8 md:h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Daftar Permohonan Izin (Semua)
                        </h3>
                        <div class="flex items-center space-x-3">
                            <span class="text-indigo-100 text-xs md:text-sm" x-text="showTable ? 'Sembunyikan' : 'Tampilkan'"></span>
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-white transition-transform duration-300" :class="{'rotate-180': showTable}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="p-6" x-show="showTable" x-transition>
                    @if($exitPermissions->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="exit-permissions-table">
                                <thead class="exit-permissions-table-head">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Siswa</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Kelas</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Alasan</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Walas</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Admin</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($exitPermissions as $permission)
                                        <tr class="exit-permissions-table-row transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('students.show', $permission->student_id) }}" class="text-custom-blue hover:text-indigo-800 font-semibold">
                                                    {{ $permission->student->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $permission->schoolClass->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $permission->exit_date->format('d M Y') }}
                                                @if($permission->exit_time)
                                                    <br><span class="text-xs text-gray-400">{{ $permission->exit_time->format('H:i') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                {{ Str::limit($permission->reason, 50) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($permission->walas_status === 'approved')
                                                    <span class="exit-status-badge exit-status-approved">Approved</span>
                                                @elseif($permission->walas_status === 'rejected')
                                                    <span class="exit-status-badge exit-status-rejected">Rejected</span>
                                                @else
                                                    <span class="exit-status-badge exit-status-pending">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($permission->admin_status === 'approved')
                                                    <span class="exit-status-badge exit-status-approved">Approved</span>
                                                @elseif($permission->admin_status === 'rejected')
                                                    <span class="exit-status-badge exit-status-rejected">Rejected</span>
                                                @else
                                                    <span class="exit-status-badge exit-status-pending">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($permission->status === 'approved')
                                                    <span class="exit-status-badge exit-status-approved">✓ Approved</span>
                                                @elseif($permission->status === 'rejected')
                                                    <span class="exit-status-badge exit-status-rejected">✗ Rejected</span>
                                                @else
                                                    <span class="exit-status-badge exit-status-pending">⏳ Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('exit-permissions.show', $permission->id) }}" class="text-custom-blue hover:text-indigo-800 font-semibold flex items-center">
                                                    Lihat
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $exitPermissions->links() }}
                        </div>
                    @else
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-lg font-semibold mb-2">Tidak ada permohonan izin</p>
                            <a href="{{ route('exit-permissions.create') }}" class="mt-4 inline-block text-custom-blue hover:text-indigo-800 font-semibold">
                                Buat permohonan pertama →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Classes Grid -->
            <div class="exit-permissions-card">
                <div class="px-6 py-4 exit-permissions-card-header">
                    <h3 class="text-lg md:text-2xl font-bold text-white flex items-center">
                        <svg class="w-7 h-7 md:w-8 md:h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Permohonan Per Kelas
                    </h3>
                    <p class="exit-permissions-subtitle mt-1 text-sm md:text-base">
                        @if(auth()->user()->isHomeroomTeacher())
                            Lihat permohonan izin keluar dari kelas Anda
                        @else
                            Pilih kelas untuk melihat permohonan izin keluar
                        @endif
                    </p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($classesWithCount as $class)
                        <a href="{{ route('exit-permissions.class-show', $class->id) }}" class="group block">
                            <div class="exit-permissions-class-card transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl">
                                <div class="p-6 exit-permissions-class-card-body">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="bg-white/15 backdrop-blur-sm rounded-xl p-3">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                        @if($class->exitPermissions->count() > 0)
                                            <span class="bg-red-500 text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg animate-pulse">
                                                {{ $class->exitPermissions->count() }}
                                            </span>
                                        @else
                                            <span class="bg-green-500 text-white px-4 py-2 rounded-full font-bold text-lg shadow-lg">
                                                ✓
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <h4 class="text-2xl font-black text-white mb-2">{{ $class->name }}</h4>
                                    <p class="exit-permissions-class-desc text-sm mb-4">{{ $class->description }}</p>
                                    
                                    <div class="space-y-3 mb-4">
                                        <div class="flex items-center text-white">
                                            <svg class="w-5 h-5 mr-3 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <span class="font-bold">{{ $class->students->count() }} Siswa</span>
                                        </div>
                                        
                                        <div class="flex items-center text-white">
                                            <svg class="w-5 h-5 mr-3 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            @if($class->exitPermissions->count() > 0)
                                                <span class="font-bold text-red-300">{{ $class->exitPermissions->count() }} Pending</span>
                                            @else
                                                <span class="font-bold text-green-300">Tidak Ada Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center justify-between pt-4 border-t border-white/20">
                                        <span class="text-white font-bold group-hover:text-gray-100 transition-colors">
                                            Lihat Detail
                                        </span>
                                        <svg class="w-6 h-6 text-white transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="col-span-full text-center py-16">
                            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <p class="text-gray-500 text-xl font-semibold">Tidak ada kelas tersedia</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
