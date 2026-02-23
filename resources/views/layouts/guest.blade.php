<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 sm:px-0 pt-6 sm:pt-0 bg-gradient-to-br from-purple-600 via-pink-500 to-red-500 animate-gradient relative overflow-hidden">
            
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-20 left-20 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-10 float-animation"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-yellow-200 rounded-full mix-blend-overlay filter blur-3xl opacity-10 float-animation" style="animation-delay: 2s;"></div>
                <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-blue-200 rounded-full mix-blend-overlay filter blur-3xl opacity-10 float-animation" style="animation-delay: 4s;"></div>
            </div>
            
            <!-- Logo & Title -->
            <div class="relative z-10 text-center mb-8">
                <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-3xl p-6 inline-block mb-4 shadow-2xl border border-white border-opacity-30">
                    <svg class="w-20 h-20 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-black text-white drop-shadow-2xl mb-2">Sistem Keterlambatan Siswa</h1>
                <p class="text-white text-opacity-90 text-lg drop-shadow-lg">Sekolah Digital - Modern & Efisien</p>
            </div>

            <!-- Login Card -->
            <div class="w-full sm:max-w-md relative z-10">
                <div class="bg-white bg-opacity-95 backdrop-blur-xl shadow-2xl overflow-hidden rounded-3xl border-4 border-white border-opacity-50 transform hover:scale-105 transition-transform duration-300">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-center">
                        <h2 class="text-2xl font-bold text-white">🔐 Selamat Datang</h2>
                        <p class="text-purple-100 mt-1">Silakan login untuk melanjutkan</p>
                    </div>
                    <div class="px-8 py-8">
                        {{ $slot }}
                    </div>
                </div>
                
                <!-- Info Footer -->
                <div class="mt-6 text-center text-white text-sm bg-white bg-opacity-20 backdrop-blur-lg rounded-2xl p-4">
                    <p class="font-semibold mb-2">🎓 Login sebagai:</p>
                    <div class="space-y-1 text-xs">
                        <p>👨‍💼 <strong>Admin:</strong> admin@school.com</p>
                        <p>👨‍🏫 <strong>Guru:</strong> teacher@school.com</p>
                        <p>🏠 <strong>Wali Kelas:</strong> homeroom.pplg@school.com</p>
                        <p class="mt-2 text-yellow-200">🔑 Password: <strong>password</strong></p>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="relative z-10 mt-8 text-white text-opacity-80 text-sm">
                <p>© 2026 Sistem Keterlambatan Siswa. All rights reserved.</p>
            </div>
        </div>
        
        <style>
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .animate-gradient {
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
            }
        </style>
    </body>
</html>
