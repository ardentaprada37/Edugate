<x-app-layout>
    @php
        $canManageStudents = !auth()->user()->isWalas();
    @endphp

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Students
            </h2>
            @if($canManageStudents)
                <a href="{{ route('admin.students.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add New Student
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
            @endif

            @if(session('import_summary'))
            @php
                $summary = session('import_summary');
            @endphp
            <div class="mb-4 bg-indigo-50 border border-indigo-200 text-indigo-700 px-4 py-3 rounded relative">
                <p class="font-semibold">Ringkasan Import</p>
                <p>Total baris file: {{ $summary['total_rows'] ?? 0 }}</p>
                <p>Berhasil diimport: {{ $summary['imported_rows'] ?? 0 }}</p>
                <p>Dilewati: {{ $summary['skipped_rows'] ?? 0 }}</p>
                <p>Nomor siswa digenerate otomatis: {{ $summary['generated_student_numbers'] ?? 0 }}</p>
                @if(!empty($summary['errors']))
                <div class="mt-2">
                    <p class="font-medium">Contoh alasan baris dilewati:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach(array_slice($summary['errors'], 0, 8) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @if(count($summary['errors']) > 8)
                        <p class="text-sm mt-1">Dan {{ count($summary['errors']) - 8 }} baris lainnya.</p>
                    @endif
                </div>
                @endif
            </div>
            @endif

            @if(!$canManageStudents)
            <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded relative">
                Akun walas hanya dapat melihat data siswa.
            </div>
            @endif

            @if($canManageStudents)
            <style>
                .student-import-card {
                    border: 1px solid #c7d7ff;
                    border-radius: 16px;
                    overflow: hidden;
                    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
                    box-shadow: 0 12px 28px rgba(11, 45, 75, 0.08);
                }
                .student-import-header {
                    background: linear-gradient(135deg, #0f4c81 0%, #0b2d4b 100%);
                    color: #ffffff !important;
                    padding: 18px 22px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 12px;
                    flex-wrap: wrap;
                }
                .student-import-title {
                    margin: 0;
                    font-size: 1.1rem;
                    font-weight: 700;
                    color: #ffffff !important;
                }
                .student-import-subtitle {
                    margin: 4px 0 0;
                    color: rgba(255, 255, 255, 0.92) !important;
                    font-size: 0.875rem;
                }
                .student-import-template-link {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    background: #ffffff;
                    color: #0b2d4b !important;
                    border: 1px solid rgba(11, 45, 75, 0.2);
                    border-radius: 10px;
                    padding: 10px 14px;
                    font-weight: 700;
                    font-size: 0.8125rem;
                    text-decoration: none !important;
                    white-space: nowrap;
                }
                .student-import-template-link:hover {
                    background: #eef6ff;
                }
                .student-import-body {
                    padding: 20px 22px;
                }
                .student-import-form {
                    display: grid;
                    gap: 12px;
                }
                .student-import-input-wrap label {
                    font-size: 0.875rem;
                    font-weight: 700;
                    color: #1f2937;
                }
                .student-import-input {
                    width: 100%;
                    margin-top: 8px;
                    border: 2px dashed #8ab4f8;
                    background: #f2f7ff;
                    border-radius: 12px;
                    padding: 12px;
                    color: #0b2d4b;
                    font-weight: 600;
                }
                .student-import-input:focus {
                    outline: none;
                    border-color: #0f4c81;
                    box-shadow: 0 0 0 4px rgba(15, 76, 129, 0.15);
                }
                .student-import-status {
                    font-size: 0.8125rem;
                    color: #334155;
                    margin-top: 8px;
                }
                .student-import-actions {
                    display: flex;
                    justify-content: flex-end;
                }
                .student-import-submit {
                    display: inline-flex !important;
                    align-items: center;
                    justify-content: center;
                    min-height: 48px;
                    min-width: 230px;
                    padding: 0 18px;
                    border-radius: 12px;
                    border: 1px solid #166534;
                    background: #16a34a !important;
                    color: #ffffff !important;
                    font-weight: 700;
                    letter-spacing: 0.01em;
                    box-shadow: 0 8px 18px rgba(22, 163, 74, 0.28);
                    visibility: visible !important;
                    opacity: 1 !important;
                }
                .student-import-submit:hover {
                    background: #15803d !important;
                }
                .student-import-submit:disabled {
                    background: #94a3b8 !important;
                    border-color: #64748b;
                    box-shadow: none;
                    cursor: not-allowed;
                }
                .student-import-notes {
                    margin-top: 14px;
                    border: 1px solid #dbeafe;
                    background: #f8fbff;
                    border-radius: 12px;
                    padding: 12px 14px;
                    color: #334155;
                    font-size: 0.875rem;
                }
                .student-import-notes ul {
                    margin: 8px 0 0 18px;
                    padding: 0;
                }
                .student-import-notes li {
                    margin-bottom: 4px;
                }
                @media (max-width: 640px) {
                    .student-import-header,
                    .student-import-body {
                        padding: 14px;
                    }
                    .student-import-submit {
                        width: 100%;
                        min-width: 0;
                    }
                }
            </style>
            <div class="mb-6 student-import-card">
                <div class="student-import-header">
                    <div>
                        <h3 class="student-import-title">Upload Dokumen Siswa</h3>
                        <p class="student-import-subtitle">Format didukung: CSV, XLSX, PDF (text-based)</p>
                    </div>
                    <a href="{{ route('admin.students.import-template') }}" class="student-import-template-link">
                        Download Template CSV
                    </a>
                </div>

                <div class="student-import-body">
                    <form id="student-import-form" action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="student-import-form">
                        @csrf
                        <div class="student-import-input-wrap">
                            <label for="import_file">Pilih File Dokumen</label>
                            <input id="import_file" name="import_file" type="file" accept=".csv,.xlsx,.pdf" required class="student-import-input">
                            <x-input-error :messages="$errors->get('import_file')" class="mt-2" />
                            <p id="student-import-status" class="student-import-status">Pilih file lalu klik tombol hijau. Sistem juga auto-kirim 0.8 detik setelah file dipilih.</p>
                        </div>

                        <div class="student-import-actions">
                            <button id="student-import-submit" type="submit" class="student-import-submit" disabled>
                                Import Data Siswa
                            </button>
                        </div>
                    </form>

                    <div class="student-import-notes">
                        <strong>Ketentuan Import:</strong>
                        <ul>
                            <li>Kolom minimal: <strong>nama, no_hp_ortu, kelas</strong>.</li>
                            <li>No HP orang tua wajib ada. Jika kosong, baris siswa dilewati.</li>
                            <li>Kelas dibaca otomatis dari dokumen. Jika tidak dikenali, baris dilewati.</li>
                            <li>Jika nomor siswa/NIS kosong, sistem akan generate otomatis.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const importForm = document.getElementById('student-import-form');
                    const importFileInput = document.getElementById('import_file');
                    const importSubmitButton = document.getElementById('student-import-submit');
                    const importStatus = document.getElementById('student-import-status');
                    let autoSubmitTimer = null;

                    if (!importForm || !importFileInput || !importSubmitButton || !importStatus) {
                        return;
                    }

                    const setUploadingState = function () {
                        importSubmitButton.disabled = true;
                        importSubmitButton.textContent = 'Mengupload...';
                        importStatus.textContent = 'Dokumen sedang diproses, mohon tunggu...';
                    };

                    importFileInput.addEventListener('change', function () {
                        if (!importFileInput.files || importFileInput.files.length === 0) {
                            importSubmitButton.disabled = true;
                            importSubmitButton.textContent = 'Import Data Siswa';
                            importStatus.textContent = 'Pilih file lalu klik tombol hijau. Sistem juga auto-kirim 0.8 detik setelah file dipilih.';
                            return;
                        }

                        const selectedName = importFileInput.files[0].name;
                        importSubmitButton.disabled = false;
                        importSubmitButton.textContent = 'Import Sekarang';
                        importStatus.textContent = 'File terpilih: ' + selectedName + '. Auto upload akan berjalan...';

                        if (autoSubmitTimer) {
                            clearTimeout(autoSubmitTimer);
                        }

                        autoSubmitTimer = setTimeout(function () {
                            setUploadingState();
                            importForm.submit();
                        }, 800);
                    });

                    importForm.addEventListener('submit', function () {
                        setUploadingState();
                    });
                });
            </script>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                    @if($canManageStudents)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($students as $student)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->student_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->schoolClass->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->gender }}</td>
                                    @if($canManageStudents)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.students.edit', $student->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ $canManageStudents ? 5 : 4 }}" class="px-6 py-4 text-center text-sm text-gray-500">No students found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
