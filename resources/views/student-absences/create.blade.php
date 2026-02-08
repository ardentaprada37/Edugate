<x-app-layout>
    <x-slot name="header">
        <div class="late-attendance-hero -mt-6 -mx-6 px-6 py-8 mb-6 shadow-lg">
            <div class="max-w-7xl mx-auto late-attendance-hero-inner flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="text-left">
                    <h2 class="font-bold text-3xl md:text-4xl text-white leading-tight">
                        {{ __('Input Ketidakhadiran Siswa') }}
                    </h2>
                    <p class="late-attendance-hero-subtitle mt-2 text-sm md:text-base">
                        Catat Sakit / Izin / Alpa berdasarkan kelas
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 min-h-screen late-attendance-bg">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 walas-alert walas-alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 walas-alert walas-alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="late-attendance-card">
                <div class="p-6">
                    <form method="GET" action="{{ route('student-absences.create') }}" class="space-y-4" id="absenceFilterForm">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" name="date" id="date" value="{{ $date }}" class="late-attendance-input mt-1">
                            </div>

                            <div>
                                <label for="grade" class="block text-sm font-medium text-gray-700">Kelas</label>
                                <select name="grade" id="grade" class="late-attendance-input mt-1">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($grades as $g)
                                        <option value="{{ $g }}" {{ ($grade ?? '') == $g ? 'selected' : '' }}>
                                            {{ $g }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="major" class="block text-sm font-medium text-gray-700">Jurusan</label>
                                <select name="major" id="major" class="late-attendance-input mt-1">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach($majors as $m)
                                        <option value="{{ $m }}" {{ ($major ?? '') == $m ? 'selected' : '' }}>
                                            {{ $m }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button type="submit" class="late-attendance-primary-btn w-full flex justify-center items-center">
                                    <i class="fas fa-search mr-2"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($selectedClass) && $selectedClass)
                <div class="late-attendance-card mt-6">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Data Siswa</h3>
                                <div class="text-sm text-gray-500">{{ $selectedClass->name }} • {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('student-absences.store') }}" id="absenceStoreForm">
                            @csrf
                            <input type="hidden" name="absence_date" value="{{ $date }}">
                            <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">

                            <div class="overflow-x-auto">
                                <table class="late-attendance-table">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-left">Nama</th>
                                            <th class="px-6 py-3 text-left">Kelas</th>
                                            <th class="px-6 py-3 text-left">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($students as $student)
                                            @php
                                                $currentStatus = $existingAbsences[$student->id]->status ?? '';
                                            @endphp
                                            <tr class="late-attendance-table-row">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $selectedClass->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="hidden" name="statuses[{{ $student->id }}]" id="status-{{ $student->id }}" value="{{ $currentStatus }}">
                                                    <div class="inline-flex items-center gap-2">
                                                        <button type="button" data-student="{{ $student->id }}" data-status="S"
                                                            class="absence-btn px-4 py-2 rounded-lg border text-sm font-bold transition"
                                                            style="border-color:#3b82f6; color:#3b82f6;">
                                                            S
                                                        </button>
                                                        <button type="button" data-student="{{ $student->id }}" data-status="I"
                                                            class="absence-btn px-4 py-2 rounded-lg border text-sm font-bold transition"
                                                            style="border-color:#f59e0b; color:#f59e0b;">
                                                            I
                                                        </button>
                                                        <button type="button" data-student="{{ $student->id }}" data-status="A"
                                                            class="absence-btn px-4 py-2 rounded-lg border text-sm font-bold transition"
                                                            style="border-color:#ef4444; color:#ef4444;">
                                                            A
                                                        </button>
                                                        <button type="button" data-student="{{ $student->id }}" data-status="T"
                                                            class="absence-btn px-4 py-2 rounded-lg border text-sm font-bold transition"
                                                            style="border-color:#8b5cf6; color:#8b5cf6;">
                                                            T
                                                        </button>
                                                        <button type="button" data-student="{{ $student->id }}" data-status="D"
                                                            class="absence-btn px-4 py-2 rounded-lg border text-sm font-bold transition"
                                                            style="border-color:#10b981; color:#10b981;">
                                                            D
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">Tidak ada siswa pada kelas ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="late-attendance-primary-btn px-6 py-3 flex items-center">
                                    <i class="fas fa-save mr-2"></i>Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @elseif(($grade ?? null) && ($major ?? null))
                <div class="late-attendance-card mt-6">
                    <div class="p-6">
                        <div class="text-sm text-gray-500">Kombinasi kelas dan jurusan tidak ditemukan.</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        (function () {
            function setActiveButtons(studentId, status) {
                const buttons = document.querySelectorAll(`.absence-btn[data-student="${studentId}"]`);
                buttons.forEach(btn => {
                    const btnStatus = btn.getAttribute('data-status');
                    let baseColor = '#3b82f6'; // S - blue
                    if (btnStatus === 'I') baseColor = '#f59e0b'; // I - orange
                    else if (btnStatus === 'A') baseColor = '#ef4444'; // A - red
                    else if (btnStatus === 'T') baseColor = '#8b5cf6'; // T - purple
                    else if (btnStatus === 'D') baseColor = '#10b981'; // D - green
                    
                    if (btnStatus === status && status) {
                        btn.style.backgroundColor = baseColor;
                        btn.style.color = '#ffffff';
                    } else {
                        btn.style.backgroundColor = 'transparent';
                        btn.style.color = baseColor;
                    }
                });
            }

            function initFromExisting() {
                const inputs = document.querySelectorAll('input[id^="status-"]');
                inputs.forEach(input => {
                    const id = input.id.replace('status-', '');
                    const value = input.value;
                    if (value) {
                        setActiveButtons(id, value);
                    }
                });
            }

            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.absence-btn');
                if (!btn) return;

                const studentId = btn.getAttribute('data-student');
                const status = btn.getAttribute('data-status');
                const input = document.getElementById(`status-${studentId}`);
                if (!input) return;

                const next = input.value === status ? '' : status;
                input.value = next;
                setActiveButtons(studentId, next);
            });

            initFromExisting();
        })();
    </script>
</x-app-layout>
