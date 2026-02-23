<nav x-data="{ open: false }" class="navbar-primary shadow-2xl border-b-4 border-white border-opacity-20">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center group">
                        <div class="bg-white bg-opacity-20 backdrop-blur-lg rounded-2xl p-3 mr-3 group-hover:scale-110 transition-transform duration-300">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="block">
                            <div class="text-white font-black text-xl drop-shadow-lg">EduGate.</div>
                            <div class="text-blue-100 text-xs">Digital School System</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden lg:!flex space-x-2 lg:-my-px lg:ms-10 items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="!text-white hover:!bg-white hover:!bg-opacity-20 !border-transparent hover:!border-white">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </x-nav-link>

                    @if(!Auth::user()->isWalas())
                    <x-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.*')" class="!text-white hover:!bg-white hover:!bg-opacity-20 !border-transparent hover:!border-white">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Catat Telat
                    </x-nav-link>

                    @if(Auth::user()->isAdmin() || Auth::user()->isTeacher())
                        <x-nav-link :href="route('student-absences.create')" :active="request()->routeIs('student-absences.*')" class="!text-white hover:!bg-white hover:!bg-opacity-20 !border-transparent hover:!border-white">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3M9 20H7a2 2 0 01-2-2V6a2 2 0 012-2h7a2 2 0 012 2v7"></path>
                            </svg>
                            Input Ketidakhadiran
                        </x-nav-link>
                    @endif
                    <!-- Late Attendance Dropdown -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:bg-white hover:bg-opacity-20 focus:outline-none transition ease-in-out duration-150">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Laporan
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border">
                            <div class="py-1">
                                <a href="{{ route('late-attendance.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('late-attendance.index') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-list mr-2"></i>Data Keterlambatan
                                </a>
                                <a href="{{ route('late-attendance.report') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('late-attendance.report') ? 'bg-gray-100' : '' }}">
                                    <i class="fas fa-chart-bar mr-2"></i>Laporan Keterlambatan & Ketidakhadiran
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(Auth::user()->isAdmin() || Auth::user()->isTeacher() || Auth::user()->isWalas() || Auth::user()->isHomeroomTeacher())
                        <x-nav-link :href="route('admin.students.index')" :active="request()->routeIs('admin.students.*')" class="!text-white hover:!bg-white hover:!bg-opacity-20 !border-transparent hover:!border-white">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Kelola Data Siswa
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('exit-permissions.index')" :active="request()->routeIs('exit-permissions.*')" class="!text-white hover:!bg-white hover:!bg-opacity-20 !border-transparent hover:!border-white">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Izin Keluar
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:!flex lg:items-center lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-lg text-white font-bold rounded-xl hover:bg-opacity-30 focus:outline-none transition ease-in-out duration-150 border border-white border-opacity-30">
                            <div class="bg-white bg-opacity-30 rounded-lg p-1 mr-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:!hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:!hidden header-primary border-t border-white/10 mobile-nav-menu">
        <div class="pt-2 pb-3 space-y-1 mobile-nav-group">
            <a href="{{ route('dashboard') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out mobile-nav-link {{ request()->routeIs('dashboard') ? 'border-white text-white bg-white/20' : 'border-transparent text-blue-100 hover:text-white hover:bg-white/10 hover:border-blue-300' }}">
                {{ __('Dashboard') }}
            </a>

            @if(!Auth::user()->isWalas())
            <a href="{{ route('classes.index') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out mobile-nav-link {{ request()->routeIs('classes.*') ? 'border-white text-white bg-white/20' : 'border-transparent text-blue-100 hover:text-white hover:bg-white/10 hover:border-blue-300' }}">
                {{ __('Catat Siswa Telat') }}
            </a>

            @if(Auth::user()->isAdmin() || Auth::user()->isTeacher())
                <a href="{{ route('student-absences.create') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out mobile-nav-link {{ request()->routeIs('student-absences.*') ? 'border-white text-white bg-white/20' : 'border-transparent text-blue-100 hover:text-white hover:bg-white/10 hover:border-blue-300' }}">
                    {{ __('Input Ketidakhadiran') }}
                </a>
            @endif
            <div class="border-t border-white/10 my-2 mobile-nav-section-divider"></div>
            <div class="px-4 py-2 text-xs font-semibold text-blue-200 uppercase tracking-wider mobile-nav-section-label">
                Laporan
            </div>
            <a href="{{ route('late-attendance.index') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out mobile-nav-link {{ request()->routeIs('late-attendance.index') ? 'border-white text-white bg-white/20' : 'border-transparent text-blue-100 hover:text-white hover:bg-white/10 hover:border-blue-300' }}">
                {{ __('Data Keterlambatan') }}
            </a>
            <a href="{{ route('late-attendance.report') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out mobile-nav-link {{ request()->routeIs('late-attendance.report') ? 'border-white text-white bg-white/20' : 'border-transparent text-blue-100 hover:text-white hover:bg-white/10 hover:border-blue-300' }}">
                {{ __('Laporan Keterlambatan & Ketidakhadiran') }}
            </a>
            <div class="border-t border-white/10 my-2 mobile-nav-section-divider"></div>
            @endif

            @if(Auth::user()->isAdmin() || Auth::user()->isTeacher() || Auth::user()->isWalas() || Auth::user()->isHomeroomTeacher())
                <a href="{{ route('admin.students.index') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out mobile-nav-link {{ request()->routeIs('admin.students.*') ? 'border-white text-white bg-white/20' : 'border-transparent text-blue-100 hover:text-white hover:bg-white/10 hover:border-blue-300' }}">
                    {{ __('Kelola Data Siswa') }}
                </a>
            @endif

            <a href="{{ route('exit-permissions.index') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out mobile-nav-link {{ request()->routeIs('exit-permissions.*') ? 'border-white text-white bg-white/20' : 'border-transparent text-blue-100 hover:text-white hover:bg-white/10 hover:border-blue-300' }}">
                {{ __('Izin Keluar Siswa') }}
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 mobile-nav-account">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-blue-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1 mobile-nav-group mobile-nav-account-links">
                <a href="{{ route('profile.edit') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out border-transparent text-blue-100 hover:text-white hover:bg-white/10 hover:border-blue-300 mobile-nav-link">
                    {{ __('Profile') }}
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out border-transparent text-blue-100 hover:text-white hover:bg-white/10 hover:border-blue-300 mobile-nav-link">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>

    <style>
        @media (max-width: 768px) {
            .mobile-nav-menu {
                padding-left: 0;
                padding-right: 0;
            }

            .mobile-nav-group {
                margin-left: 12px;
                margin-right: 12px;
                border-radius: 12px;
                overflow: hidden;
            }

            .mobile-nav-link {
                padding-top: 13px !important;
                padding-bottom: 13px !important;
                padding-left: 16px !important;
                padding-right: 16px !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .mobile-nav-group > .mobile-nav-link:last-child {
                border-bottom: 0;
            }

            .mobile-nav-section-divider {
                margin-top: 14px !important;
                margin-bottom: 14px !important;
            }

            .mobile-nav-section-label {
                font-size: 10px;
                letter-spacing: 0.14em;
                opacity: 0.72;
                padding-top: 0;
                padding-bottom: 8px;
            }

            .mobile-nav-account {
                margin-top: 16px;
                padding-top: 18px;
                border-top-color: rgba(255, 255, 255, 0.16);
            }

            .mobile-nav-account-links {
                margin-top: 12px !important;
                margin-bottom: 8px;
            }

            .mobile-nav-account-links form .mobile-nav-link {
                border-bottom: 0;
            }
        }
    </style>
</nav>
