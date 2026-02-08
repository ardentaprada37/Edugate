<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Terlambat dan Ketidakhadiran - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .report-date {
            font-size: 14px;
            color: #666;
        }
        
        .summary-section {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #ddd;
        }
        
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .summary-item {
            padding: 8px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .summary-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        td {
            font-size: 10px;
        }
        
        .class-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .class-header {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            background-color: #e9ecef;
            padding: 8px;
            border-left: 4px solid #007bff;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-excused {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .late-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .late-severe {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .late-moderate {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 50px;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 50px;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            height: 40px;
        }
        
        .signature-name {
            font-size: 11px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="school-name">SMK PARIWISATA METLAND</div>
        <div class="report-title">LAPORAN TERLAMBAT DAN KETIDAKHADIRAN</div>
        <div class="report-date">{{ $date }}</div>
        @if($className)
            <div class="report-date">Kelas: {{ $className }}</div>
        @endif
    </div>
    
    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-title">Ringkasan Laporan</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Siswa Terlambat</div>
                <div class="summary-value">{{ $totalLateStudents }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Siswa Tidak Hadir</div>
                <div class="summary-value">{{ $totalAbsentStudents ?? 0 }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Berizin</div>
                <div class="summary-value">{{ $totalExcused }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Kelas Terdampak</div>
                <div class="summary-value">{{ $latePerClass->count() }}</div>
            </div>
        </div>
    </div>

    <div class="summary-section">
        <div class="summary-title">Guru Piket</div>
        @if(($picketTeachers ?? collect())->count() > 0)
            <ol style="margin: 0; padding-left: 18px;">
                @foreach($picketTeachers as $name)
                    <li>{{ $name }}</li>
                @endforeach
            </ol>
        @else
            <div style="color: #666;">Belum ada data guru piket.</div>
        @endif
    </div>

    <div class="summary-section">
        <div class="summary-title">Guru Tidak Hadir</div>
        @if(($teacherAbsences ?? collect())->count() > 0)
            <ol style="margin: 0; padding-left: 18px;">
                @foreach($teacherAbsences as $ta)
                    <li>{{ $ta->teacher_name }}{{ $ta->reason ? ' (' . $ta->reason . ')' : '' }}</li>
                @endforeach
            </ol>
        @else
            <div style="color: #666;">Tidak ada data guru tidak hadir.</div>
        @endif
    </div>
    
    @if($type === 'class' && $groupedData->count() > 0)
        <!-- Grouped by Class Report -->
        @foreach($groupedData as $className => $classAttendances)
        <div class="class-section">
            <div class="class-header">
                {{ $className }} ({{ $classAttendances->count() }} siswa)
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 30%;">Nama Siswa</th>
                        <th style="width: 20%;">Waktu Datang</th>
                        <th style="width: 25%;">Alasan Terlambat</th>
                        <th style="width: 20%;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classAttendances as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attendance->student->name }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($attendance->arrival_time)->format('H:i') }}
                            @php
                                $arrivalTime = \Carbon\Carbon::parse($attendance->arrival_time);
                                $schoolStart = \Carbon\Carbon::parse($attendance->late_date)->setTime(7, 0, 0);
                                $minutesLate = $schoolStart->diffInMinutes($arrivalTime);
                            @endphp
                            <br>
                            <span class="late-badge {{ $minutesLate > 30 ? 'late-severe' : 'late-moderate' }}">
                                {{ $minutesLate }} menit
                            </span>
                        </td>
                        <td>{{ $attendance->lateReason->reason ?? 'N/A' }}</td>
                        <td>{{ $attendance->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    @else
        <!-- Standard Report -->
        @if($lateAttendances->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Siswa</th>
                    <th style="width: 20%;">Kelas</th>
                    <th style="width: 20%;">Waktu Datang</th>
                    <th style="width: 25%;">Alasan Terlambat</th>
                    <th style="width: 15%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lateAttendances as $index => $attendance)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $attendance->student->name }}</td>
                    <td>{{ $attendance->schoolClass->name }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($attendance->arrival_time)->format('H:i') }}
                        @php
                            $arrivalTime = \Carbon\Carbon::parse($attendance->arrival_time);
                            $schoolStart = \Carbon\Carbon::parse($attendance->late_date)->setTime(7, 0, 0);
                            $minutesLate = $schoolStart->diffInMinutes($arrivalTime);
                        @endphp
                        <br>
                        <span class="late-badge {{ $minutesLate > 30 ? 'late-severe' : 'late-moderate' }}">
                            {{ $minutesLate }} menit
                        </span>
                    </td>
                    <td>{{ $attendance->lateReason->reason ?? 'N/A' }}</td>
                    <td>{{ $attendance->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <p>Tidak ada data keterlambatan untuk tanggal {{ $date }}</p>
        </div>
        @endif
    @endif

    <!-- Student Absence Report (S/I/A) -->
    <div class="class-section">
        <div class="class-header" style="border-left-color: #f59e0b;">
            KETIDAKHADIRAN SISWA (S / I / A / T / D)
        </div>

        @if(($groupedAbsences ?? collect())->count() > 0)
            @foreach($groupedAbsences as $className => $classAbsences)
                <div class="class-section">
                    <div class="class-header" style="border-left-color: #f59e0b;">
                        {{ $className }} ({{ $classAbsences->count() }} siswa)
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 45%;">Nama Siswa</th>
                                <th style="width: 20%;">Status</th>
                                <th style="width: 30%;">Dicatat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classAbsences as $index => $absence)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $absence->student->name }}</td>
                                    <td>
                                        @if($absence->status === 'S')
                                            Sakit
                                        @elseif($absence->status === 'I')
                                            Izin
                                        @elseif($absence->status === 'A')
                                            Alpa
                                        @elseif($absence->status === 'T')
                                            Tefa
                                        @elseif($absence->status === 'D')
                                            Dispen
                                        @endif
                                    </td>
                                    <td>{{ $absence->recordedBy->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        @else
            <div style="text-align: center; padding: 18px; color: #666;">
                Tidak ada data ketidakhadiran untuk tanggal {{ $date }}
            </div>
        @endif
    </div>
    
    
    <!-- Footer -->
    <div class="footer">
        <p>Laporan dibuat otomatis pada {{ now()->format('d F Y H:i') }} WIB</p>
        <p>Sistem Manajemen Keterlambatan - SMK Pariwisata Metland</p>
    </div>
</body>
</html>
