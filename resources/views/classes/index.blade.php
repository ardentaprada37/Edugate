<x-app-layout>
    <x-slot name="header">
        <div class="bg-custom-blue -mt-6 -mx-6 px-6 py-8 mb-6 shadow-md flex items-center">
            <h2 class="font-bold text-3xl text-white leading-tight drop-shadow-md">
                Catat Siswa Yang Telat
            </h2>
        </div>
    </x-slot>

    <div class="py-6 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header for the grid section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div class="w-full md:w-auto min-w-0">
                     <h3 class="text-xl font-bold text-gray-900 leading-none break-words">Daftar Kelas</h3>
                     <p class="text-gray-500 text-sm mt-2 break-words">Klik kartu kelas untuk melihat daftar siswa</p>
                </div>
                
                <!-- Button Cari & Pilih Siswa -->
                <a href="{{ route('late-attendance.multi-create') }}" class="w-full md:w-auto inline-flex items-center justify-center text-center bg-custom-blue text-white px-6 py-3 rounded-full font-bold text-sm hover:scale-105 active:scale-95 hover:shadow-xl transition-all duration-300 shadow-md">
                    Cari & Pilih Siswa
                </a>
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($classes as $class)
                <a href="{{ route('classes.show', $class->id) }}" class="block bg-card-gray rounded-3xl p-6 border-b-4 border-gray-400 hover:border-b-8 hover:-translate-y-2 hover:shadow-2xl active:scale-95 active:border-b-4 active:translate-y-0 transition-all duration-300 cubic-bezier(0.34, 1.56, 0.64, 1) relative group">
                    <div class="flex justify-between items-start mb-2">
                        <!-- Icon -->
                        <div class="bg-white p-3 rounded-2xl shadow-sm text-custom-blue group-hover:scale-110 transition-transform duration-300">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        
                        <!-- Pill -->
                        <span class="inline-block bg-custom-blue text-white text-xs font-bold px-4 py-2 rounded-full shadow-sm group-hover:scale-110 transition-transform duration-300">
                            {{ $class->students->count() }} siswa
                        </span>
                    </div>

                    <!-- Title -->
                    <h5 class="text-xl font-black italic text-gray-900 mb-1 mt-4">
                        {{ strtoupper($class->name) }}
                    </h5>
                    <!-- Subtitle -->
                    <p class="text-sm italic text-gray-600 font-medium">
                        {{ $class->description ?? 'Teknik informatika' }}
                    </p>
                </a>
                @endforeach
            </div>

             @if($classes->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-3xl mt-8">
                <p class="text-gray-500 text-xl">Tidak ada kelas yang tersedia</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
