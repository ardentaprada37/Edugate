<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-green-600 to-teal-600 -mt-6 -mx-6 px-6 py-8 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-3xl text-white leading-tight drop-shadow-lg flex items-center">
                        <svg class="w-10 h-10 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Persetujuan Izin Keluar Per Kelas
                    </h2>
                    <p class="text-green-100 mt-2">Pilih kelas untuk melihat permohonan izin keluar yang perlu disetujui</p>
                </div>
                <a href="{{ route('exit-permissions.index') }}" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-bold py-3 px-6 rounded-xl transition duration-300 flex items-center backdrop-blur-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    Lihat Semua
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-green-50 via-teal-50 to-blue-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Info Card -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-3xl shadow-2xl p-6 mb-8 text-white">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-30 rounded-full p-4 mr-4">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xl font-bold">ℹ️ Informasi</p>
                        <p class="text-blue-100 mt-1">
                            @if(auth()->user()->isHomeroomTeacher())
                                Pilih kelas Anda untuk melihat dan menyetujui permohonan izin keluar siswa
                            @else
                                Pilih kelas untuk melihat semua permohonan izin keluar yang pending
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Classes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($classes as $class)
                <a href="{{ route('exit-permissions.class-show', $class->id) }}" class="group block">
                    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-green-200 hover:border-green-400 transition-all duration-300 transform hover:scale-105 hover:shadow-3xl">
                        <div class="bg-gradient-to-r from-green-500 to-teal-500 p-6 relative overflow-hidden">
                            <!-- Background Pattern -->
                            <div class="absolute inset-0 opacity-10">
                                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                                    <pattern id="pattern-{{ $class->id }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                        <circle cx="2" cy="2" r="1" fill="white"/>
                                    </pattern>
                                    <rect x="0" y="0" width="100%" height="100%" fill="url(#pattern-{{ $class->id }})"/>
                                </svg>
                            </div>
                            
                            <div class="relative">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="bg-white bg-opacity-30 rounded-2xl p-3 backdrop-blur-sm">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    @if($class->exitPermissions->count() > 0)
                                        <span class="bg-red-500 text-white px-4 py-2 rounded-full font-bold text-lg animate-pulse">
                                            {{ $class->exitPermissions->count() }}
                                        </span>
                                    @else
                                        <span class="bg-white bg-opacity-30 text-white px-4 py-2 rounded-full font-bold text-lg backdrop-blur-sm">
                                            0
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-2xl font-black text-white mb-1">{{ $class->name }}</h3>
                                <p class="text-green-100 text-sm">{{ $class->description }}</p>
                            </div>
                        </div>
                        
                        <div class="p-6 bg-gradient-to-br from-white to-gray-50">
                            <div class="space-y-3">
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span class="font-semibold">{{ $class->students->count() }} Siswa</span>
                                </div>
                                
                                <div class="flex items-center text-gray-700">
                                    <svg class="w-5 h-5 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span class="font-semibold">
                                        @if($class->exitPermissions->count() > 0)
                                            <span class="text-red-600">{{ $class->exitPermissions->count() }} Permohonan Pending</span>
                                        @else
                                            <span class="text-green-600">Tidak Ada Pending</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex items-center justify-between">
                                <span class="text-green-600 font-bold group-hover:text-green-700 transition-colors">
                                    Lihat Detail
                                </span>
                                <svg class="w-6 h-6 text-green-600 transform group-hover:translate-x-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-20">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <p class="text-gray-500 text-xl">Tidak ada kelas tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
