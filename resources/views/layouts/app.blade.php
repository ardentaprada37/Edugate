<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Sistem Keterlambatan Siswa</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
            /* Poppins Font Classes */
            .poppins-regular {
                font-family: "Poppins", sans-serif;
                font-weight: 400;
                font-style: normal;
            }
            .poppins-semibold {
                font-family: "Poppins", sans-serif;
                font-weight: 600;
                font-style: normal;
            }
            .poppins-bold {
                font-family: "Poppins", sans-serif;
                font-weight: 700;
                font-style: normal;
            }
            /* Navbar with primary color */
            .navbar-primary {
                background-color: #160B6A !important;
                position: relative;
                z-index: 999;
            }
            /* Card with gradient from #231591 to #0A062B */
            .card-primary {
                background: linear-gradient(135deg, #231591 0%, #0A062B 100%) !important;
                color: white !important;
            }
            .card-primary * {
                color: white !important;
            }
            /* Header with gradient color */
            .header-primary {
                background: linear-gradient(135deg, #231591 0%, #0A062B 100%) !important;
                color: white !important;
            }
            .header-primary * {
                color: white !important;
            }
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .animate-gradient {
                background-size: 200% 200%;
                animation: gradient 15s ease infinite;
            }
            .glass-effect {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            .bg-custom-blue {
                background-color: #160B6A !important;
            }
            .text-custom-blue {
                color: #160B6A !important;
            }
            .bg-card-gray {
                background-color: #E5E5E5 !important;
            }

            .exit-permissions-bg {
                background:
                    radial-gradient(circle at 10% 10%, rgba(126, 123, 149, 0.14) 0%, rgba(126, 123, 149, 0) 42%),
                    radial-gradient(circle at 90% 15%, rgba(22, 11, 106, 0.10) 0%, rgba(22, 11, 106, 0) 45%),
                    #F4F2FF;
            }

            .exit-permissions-card {
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.12);
                border-radius: 16px;
                box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
                overflow: hidden;
            }

            .exit-permissions-card-header {
                background: linear-gradient(90deg, #160B6A 0%, #160B6A 67%);
            }

            .exit-permissions-primary-btn {
                background: #160B6A;
                color: #ffffff;
            }

            .exit-permissions-secondary-btn {
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.25);
                color: #160B6A;
            }

            .exit-permissions-header-btn {
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.45);
                color: #ffffff;
            }

            .exit-permissions-header-btn:hover {
                background: rgba(255, 255, 255, 0.20);
            }

            .exit-permissions-label {
                color: rgba(17, 24, 39, 0.78);
                font-weight: 600;
            }

            .exit-permissions-input {
                width: 100%;
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.20);
                border-radius: 12px;
                color: #111827;
            }

            .exit-permissions-input::placeholder {
                color: rgba(17, 24, 39, 0.45);
            }

            .exit-permissions-input:focus {
                outline: none;
                border-color: rgba(22, 11, 106, 0.65);
                box-shadow: 0 0 0 3px rgba(22, 11, 106, 0.18);
            }

            .exit-permissions-table {
                width: 100%;
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.12);
                border-radius: 14px;
                overflow: hidden;
            }

            .exit-permissions-table-row:hover {
                background: rgba(22, 11, 106, 0.04);
            }

            .exit-permissions-table-head {
                background: #160B6A;
            }

            .exit-permissions-table-head th {
                color: #ffffff;
            }

            .exit-permissions-class-card {
                background:
                    radial-gradient(circle at 100% 0%, rgba(126, 123, 149, 0.98) 0%, rgba(126, 123, 149, 0.55) 22%, rgba(126, 123, 149, 0) 45%),
                    #160B6A;
                border-radius: 16px;
                box-shadow: 0 12px 30px rgba(22, 11, 106, 0.22);
                overflow: hidden;
            }

            .exit-permissions-class-desc {
                color: rgba(255, 255, 255, 0.80);
            }

            .exit-permissions-subtitle {
                color: rgba(255, 255, 255, 0.85);
            }

            .exit-status-badge {
                display: inline-flex;
                align-items: center;
                padding: 4px 10px;
                border-radius: 9999px;
                font-size: 11px;
                font-weight: 700;
                line-height: 1;
                color: #ffffff;
            }

            .exit-status-approved {
                background: #22C55E;
            }

            .exit-status-rejected {
                background: #EF4444;
            }

            .exit-status-pending {
                background: #F59E0B;
            }

            @media (max-width: 1024px) {
                .exit-permissions-page-hero-inner {
                    gap: 14px;
                }

                .exit-permissions-class-card-body {
                    padding: 20px;
                }
            }

            @media (max-width: 768px) {
                .exit-permissions-page {
                    padding-top: 24px;
                    padding-bottom: 24px;
                }

                .exit-permissions-page-hero {
                    padding-top: 20px;
                    padding-bottom: 20px;
                }

                .exit-permissions-page-hero-inner {
                    display: flex;
                    flex-direction: column;
                    align-items: stretch;
                }

                .exit-permissions-page-title {
                    font-size: 28px;
                    line-height: 1.2;
                }

                .exit-permissions-page-hero-inner .exit-permissions-header-btn {
                    width: 100%;
                    justify-content: center;
                }

                .exit-permissions-class-card-body {
                    padding: 16px;
                }

                .exit-permissions-table th,
                .exit-permissions-table td {
                    padding: 12px 14px !important;
                }
            }

            @media (max-width: 480px) {
                .exit-permissions-page-hero {
                    margin-left: -16px;
                    margin-right: -16px;
                    padding-left: 16px;
                    padding-right: 16px;
                }

                .exit-status-badge {
                    font-size: 10px;
                    padding: 4px 8px;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="shadow-lg">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <!-- Floating Background Elements -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
            <div class="absolute top-20 left-20 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-40 right-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-20 left-40 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>
        
        <style>
            @keyframes blob {
                0%, 100% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
        
        @stack('scripts')
    </body>
</html>
