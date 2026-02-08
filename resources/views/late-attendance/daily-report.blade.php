<x-app-layout>
    <x-slot name="header">
        <div class="late-attendance-hero -mt-6 -mx-6 px-6 py-8 mb-6 shadow-lg">
            <div class="max-w-7xl mx-auto late-attendance-hero-inner flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="text-left">
                    <h2 class="font-bold text-3xl md:text-4xl text-white leading-tight">
                        {{ __('Laporan Terlambat dan Ketidakhadiran') }}
                    </h2>
                    <p class="late-attendance-hero-subtitle mt-2 text-sm md:text-base">
                        Pantau harian dan export laporan
                    </p>
                </div>
                <div class="flex space-x-2">
                    <!-- PDF Export Button -->
                    <a href="{{ route('late-attendance.export-pdf', request()->query()) }}" 
                       class="bg-white text-[#160B6A] hover:bg-gray-100 px-4 py-2 rounded-xl font-bold shadow-md transition-colors duration-200 flex items-center">
                        <i class="fas fa-file-pdf mr-2 text-red-500"></i>Export PDF
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen late-attendance-bg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Summary Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Total Late Students -->
                <div class="late-attendance-card p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-red-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 truncate">Total Siswa Terlambat</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $totalLateStudents }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Excused -->
                <div class="late-attendance-card p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 truncate">Total Izin Disetujui</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $totalExcused }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Absent Students -->
                <div class="late-attendance-card p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-times text-orange-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 truncate">Total Siswa Tidak Hadir</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $totalAbsentStudents ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Classes Affected -->
                <div class="late-attendance-card p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-graduation-cap text-purple-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 truncate">Kelas Terdampak</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $latePerClass->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Controls -->
            <div class="late-attendance-card mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('late-attendance.report') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Date Picker -->
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" 
                                       name="date" 
                                       id="date" 
                                       value="{{ $date }}" 
                                       class="late-attendance-input mt-1">
                            </div>

                            <!-- Class Filter -->
                            <div>
                                <label for="class_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                                <select name="class_id" id="class_id" class="late-attendance-input mt-1">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="late-attendance-input mt-1">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="excused" {{ $status == 'excused' ? 'selected' : '' }}>Berizin</option>
                                </select>
                            </div>

                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Cari Nama Siswa</label>
                                <input type="text" 
                                       name="search" 
                                       id="search" 
                                       value="{{ $search }}" 
                                       placeholder="Ketik nama siswa..."
                                       class="late-attendance-input mt-1">
                            </div>

                            <!-- Search Button -->
                            <div class="flex items-end">
                                <button
                                    type="submit"
                                    class="late-attendance-primary-btn w-full flex justify-center items-center"
                                >
                                    <i class="fas fa-search mr-2"></i>
                                    <span>Search</span>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Late Attendance Data -->
            @if($groupByClass && $groupedData->count() > 0)
                <!-- Grouped by Class View -->
                @foreach($groupedData as $className => $classAttendances)
                <div class="late-attendance-card mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-users mr-2"></i>{{ $className }} ({{ $classAttendances->count() }} siswa)
                        </h3>
                        
                        <div class="overflow-x-auto">
                            <table class="late-attendance-table">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Datang</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($classAttendances as $attendance)
                                    <tr class="late-attendance-table-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->student->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($attendance->arrival_time)->format('H:i') }}
                                                @php
                                                    $arrivalTime = \Carbon\Carbon::parse($attendance->arrival_time);
                                                    $schoolStart = \Carbon\Carbon::parse($attendance->late_date)->setTime(7, 0, 0);
                                                    $minutesLate = $schoolStart->diffInMinutes($arrivalTime);
                                                @endphp
                                                @if($minutesLate > 30)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        {{ $minutesLate }}min late
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        {{ $minutesLate }}min late
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $attendance->lateReason->reason ?? 'N/A' }}</div>
                                            @if($attendance->notes)
                                                <div class="text-xs text-gray-500">{{ $attendance->notes }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $attendance->recordedBy->name ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Standard Table View -->
                <div class="late-attendance-card">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Data Keterlambatan - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                            </h3>
                            <div class="text-sm text-gray-500">
                                Total: {{ $lateAttendances->count() }} siswa
                            </div>
                        </div>

                        @if($lateAttendances->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="late-attendance-table">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Datang</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dicatat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($lateAttendances as $attendance)
                                    <tr class="late-attendance-table-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->student->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $attendance->schoolClass->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($attendance->arrival_time)->format('H:i') }}
                                                @php
                                                    $arrivalTime = \Carbon\Carbon::parse($attendance->arrival_time);
                                                    $schoolStart = \Carbon\Carbon::parse($attendance->late_date)->setTime(7, 0, 0);
                                                    $minutesLate = $schoolStart->diffInMinutes($arrivalTime);
                                                @endphp
                                                @if($minutesLate > 30)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        {{ $minutesLate }}min late
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        {{ $minutesLate }}min late
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $attendance->lateReason->reason ?? 'N/A' }}</div>
                                            @if($attendance->notes)
                                                <div class="text-xs text-gray-500">{{ $attendance->notes }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $attendance->recordedBy->name ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-check text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">Tidak ada data keterlambatan untuk tanggal {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Student Absence Data (S/I/A) -->
            <div class="late-attendance-card mt-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Data Ketidakhadiran (S / I / A / T / D) - {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                        </h3>
                        <div class="text-sm text-gray-500">
                            Total: {{ $totalAbsentStudents ?? 0 }} siswa
                        </div>
                    </div>

                    @if(($groupedAbsences ?? collect())->count() > 0)
                        @foreach($groupedAbsences as $className => $classAbsences)
                            <div class="mb-6">
                                <h4 class="text-sm font-bold text-gray-900 mb-2">{{ $className }} ({{ $classAbsences->count() }} siswa)</h4>
                                <div class="overflow-x-auto">
                                    <table class="late-attendance-table">
                                        <thead>
                                            <tr>
                                                <th class="px-6 py-3 text-left">Nama Siswa</th>
                                                <th class="px-6 py-3 text-left">Status</th>
                                                <th class="px-6 py-3 text-left">Dicatat Oleh</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($classAbsences as $absence)
                                                <tr class="late-attendance-table-row">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $absence->student->name }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        @if($absence->status === 'S')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Sakit</span>
                                                        @elseif($absence->status === 'I')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Izin</span>
                                                        @elseif($absence->status === 'A')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Alpa</span>
                                                        @elseif($absence->status === 'T')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Tefa</span>
                                                        @elseif($absence->status === 'D')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Dispen</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $absence->recordedBy->name ?? 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-user-check text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-500">Tidak ada data ketidakhadiran untuk tanggal {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Picket Teachers / Teacher Absence Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div class="late-attendance-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Guru Piket</h3>
                        @if(($picketTeachers ?? collect())->count() > 0)
                            <ol class="list-decimal pl-5 space-y-1 text-sm text-gray-700">
                                @foreach($picketTeachers as $name)
                                    <li>{{ $name }}</li>
                                @endforeach
                            </ol>
                        @else
                            <p class="text-sm text-gray-500">Belum ada data guru piket.</p>
                        @endif
                    </div>
                </div>

                <div class="late-attendance-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Guru Tidak Hadir</h3>
                        @if(($teacherAbsences ?? collect())->count() > 0)
                            <ol class="list-decimal pl-5 space-y-1 text-sm text-gray-700">
                                @foreach($teacherAbsences as $ta)
                                    <li>{{ $ta->teacher_name }}{{ $ta->reason ? ' (' . $ta->reason . ')' : '' }}</li>
                                @endforeach
                            </ol>
                        @else
                            <p class="text-sm text-gray-500">Tidak ada data guru tidak hadir.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mini Statistics Section -->
            @if($monthlyStats['topLateStudents']->count() > 0 || $monthlyStats['classWithMostLate'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <!-- Top Late Students This Month -->
                @if($monthlyStats['topLateStudents']->count() > 0)
                <div class="late-attendance-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-trophy text-yellow-500 mr-2"></i>Top 3 Siswa Terlambat Bulan Ini
                        </h3>
                        <div class="space-y-3">
                            @foreach($monthlyStats['topLateStudents'] as $index => $student)
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : ($index === 1 ? 'bg-gray-100 text-gray-800' : 'bg-orange-100 text-orange-800') }} text-sm font-medium mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $student->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->schoolClass->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ $student->late_attendances_count }}x</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Class with Most Late -->
                @if($monthlyStats['classWithMostLate'])
                <div class="late-attendance-card">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-chart-line text-red-500 mr-2"></i>Kelas dengan Keterlambatan Terbanyak
                        </h3>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-red-600 mb-2">{{ $monthlyStats['classWithMostLate']->name }}</div>
                            <div class="text-sm text-gray-500 mb-4">{{ $monthlyStats['classWithMostLate']->late_attendances_count }} keterlambatan bulan ini</div>
                            <a href="{{ route('late-attendance.report', ['class_id' => $monthlyStats['classWithMostLate']->id]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>

    <script>

        // Auto-submit form on Enter key press
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.querySelector('form[action="{{ route('late-attendance.report') }}"]');
            const inputs = filterForm.querySelectorAll('input, select');
            
            inputs.forEach(function(input) {
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        filterForm.submit();
                    }
                });
            });
        });

        // Send Report Function
        function sendReport() {
            // Show send options modal/dropdown
            const sendOptions = document.getElementById('sendOptions');
            if (sendOptions) {
                sendOptions.classList.toggle('hidden');
            } else {
                // Create send options dropdown dynamically
                createSendDropdown();
            }
        }

        function createSendDropdown() {
            const sendBtn = document.querySelector('button[onclick="sendReport()"]');
            const dropdown = document.createElement('div');
            dropdown.id = 'sendOptions';
            dropdown.className = 'absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border';
            dropdown.style.position = 'absolute';
            dropdown.style.top = sendBtn.offsetTop + sendBtn.offsetHeight + 'px';
            dropdown.style.right = '0px';
            
            dropdown.innerHTML = `
                <div class="py-1">
                    <a href="#" onclick="sendViaWhatsApp()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fab fa-whatsapp mr-2 text-green-500"></i>WhatsApp
                    </a>
                    <a href="#" onclick="sendViaTelegram()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fab fa-telegram mr-2 text-blue-500"></i>Telegram
                    </a>
                    <a href="#" onclick="sendViaEmail()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-envelope mr-2 text-gray-500"></i>Email
                    </a>
                    <div class="border-t border-gray-100"></div>
                    <a href="#" onclick="copyReportLink()" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-copy mr-2 text-gray-500"></i>Copy Link
                    </a>
                </div>
            `;
            
            sendBtn.parentElement.style.position = 'relative';
            sendBtn.parentElement.appendChild(dropdown);
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!sendBtn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.remove();
                }
            });
        }

        function sendViaWhatsApp() {
            const currentUrl = window.location.href;
            const date = '{{ \Carbon\Carbon::parse($date)->format('d F Y') }}';
            const totalStudents = {{ $totalLateStudents }};
            
            const message = `📊 *Laporan Terlambat dan Ketidakhadiran*\n` +
                          `📅 Tanggal: ${date}\n` +
                          `👥 Total Siswa Terlambat: ${totalStudents} siswa\n\n` +
                          `🔗 Link Laporan: ${currentUrl}`;
            
            const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
            document.getElementById('sendOptions')?.remove();
        }

        function sendViaTelegram() {
            const currentUrl = window.location.href;
            const date = '{{ \Carbon\Carbon::parse($date)->format('d F Y') }}';
            const totalStudents = {{ $totalLateStudents }};
            
            const message = `📊 Laporan Terlambat dan Ketidakhadiran\n` +
                          `📅 Tanggal: ${date}\n` +
                          `👥 Total Siswa Terlambat: ${totalStudents} siswa\n\n` +
                          `🔗 Link: ${currentUrl}`;
            
            const telegramUrl = `https://t.me/share/url?url=${encodeURIComponent(currentUrl)}&text=${encodeURIComponent(message)}`;
            window.open(telegramUrl, '_blank');
            document.getElementById('sendOptions')?.remove();
        }

        function sendViaEmail() {
            const currentUrl = window.location.href;
            const date = '{{ \Carbon\Carbon::parse($date)->format('d F Y') }}';
            const totalStudents = {{ $totalLateStudents }};
            
            const subject = `Laporan Terlambat dan Ketidakhadiran - ${date}`;
            const body = `Laporan Terlambat dan Ketidakhadiran\n\n` +
                        `Tanggal: ${date}\n` +
                        `Total Siswa Terlambat: ${totalStudents} siswa\n\n` +
                        `Link Laporan: ${currentUrl}\n\n` +
                        `Dikirim dari Sistem Manajemen Keterlambatan - SMK Pariwisata Metland`;
            
            const mailtoUrl = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            window.location.href = mailtoUrl;
            document.getElementById('sendOptions')?.remove();
        }

        function copyReportLink() {
            const currentUrl = window.location.href;
            navigator.clipboard.writeText(currentUrl).then(function() {
                // Show success notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                notification.textContent = 'Link copied to clipboard!';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            });
            document.getElementById('sendOptions')?.remove();
        }
    </script>
</x-app-layout>

