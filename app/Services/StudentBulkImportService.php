<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Throwable;

class StudentBulkImportService
{
    private const FIELD_ALIASES = [
        'name' => ['nama', 'nama siswa', 'name', 'student name'],
        'student_number' => ['nis', 'nisn', 'student number', 'nomor siswa', 'no induk', 'no induk siswa', 'nomor induk'],
        'class_name' => ['kelas', 'nama kelas', 'class', 'class name', 'rombel', 'kelas berapa', 'kelas siswa'],
        'gender' => ['jk', 'jenis kelamin', 'gender'],
        'phone' => ['no hp', 'nomor hp', 'phone', 'telp siswa', 'telepon siswa', 'hp siswa', 'wa siswa'],
        'parent_phone' => ['no hp ortu', 'nomor hp ortu', 'parent phone', 'parent_phone', 'telp ortu', 'telepon ortu', 'wa ortu', 'whatsapp ortu'],
        'address' => ['alamat', 'address'],
    ];

    public function __construct(
        private readonly StudentImportParser $parser
    ) {
    }

    public function import(UploadedFile $file, User $user): array
    {
        $parsed = $this->parser->parse($file);
        $rows = $parsed['rows'] ?? [];

        if ($rows === []) {
            throw ValidationException::withMessages([
                'import_file' => 'Tidak ada data siswa yang bisa diproses dari file.',
            ]);
        }

        $allowedClasses = $this->getAllowedClasses($user);
        if ($allowedClasses->isEmpty()) {
            throw ValidationException::withMessages([
                'import_file' => 'Tidak ada kelas aktif yang bisa digunakan untuk impor siswa.',
            ]);
        }

        $classLookup = $this->buildClassLookup($allowedClasses);

        $result = [
            'total_rows' => count($rows),
            'imported_rows' => 0,
            'skipped_rows' => 0,
            'generated_student_numbers' => 0,
            'errors' => [],
        ];

        $usedNumbersInBatch = [];

        foreach ($rows as $index => $row) {
            $excelRowNumber = $index + 2;
            $payload = $this->mapRowToStudentData(
                row: $row,
                classLookup: $classLookup,
                user: $user,
                generatedNumberCounter: $result['generated_student_numbers'],
                errorRowNumber: $excelRowNumber
            );

            if ($payload['data'] === null) {
                $result['skipped_rows']++;
                $result['errors'][] = $payload['error'];
                continue;
            }

            $studentNumber = $payload['data']['student_number'];

            if ($this->isDuplicateStudentNumber($studentNumber, $usedNumbersInBatch)) {
                if ($payload['generated_student_number']) {
                    $studentNumber = $this->generateStudentNumber($usedNumbersInBatch);
                    $payload['data']['student_number'] = $studentNumber;
                } else {
                    $result['skipped_rows']++;
                    $result['errors'][] = "Baris {$excelRowNumber}: nomor siswa {$studentNumber} sudah ada/duplikat.";
                    continue;
                }
            }

            if ($this->isDuplicateStudentNumber($studentNumber, $usedNumbersInBatch)) {
                $result['skipped_rows']++;
                $result['errors'][] = "Baris {$excelRowNumber}: nomor siswa {$studentNumber} sudah ada/duplikat.";
                continue;
            }

            try {
                Student::create($payload['data']);
                $usedNumbersInBatch[$studentNumber] = true;
                $result['imported_rows']++;
            } catch (Throwable $exception) {
                $result['skipped_rows']++;
                $result['errors'][] = "Baris {$excelRowNumber}: gagal disimpan ({$exception->getMessage()}).";
            }
        }

        return $result;
    }

    private function getAllowedClasses(User $user): Collection
    {
        $query = SchoolClass::query()->active();

        if ($user->isClassScopedRole()) {
            if (!$user->hasAssignedClass()) {
                return collect();
            }

            $query->where('id', $user->assigned_class_id);
        }

        return $query->get();
    }

    private function mapRowToStudentData(
        array $row,
        array $classLookup,
        User $user,
        int &$generatedNumberCounter,
        int $errorRowNumber
    ): array {
        $name = $this->extractField($row, self::FIELD_ALIASES['name']);
        if ($name === null) {
            return [
                'data' => null,
                'error' => "Baris {$errorRowNumber}: nama siswa kosong.",
            ];
        }

        $parentPhone = $this->extractField($row, self::FIELD_ALIASES['parent_phone']);
        if ($parentPhone === null) {
            return [
                'data' => null,
                'error' => "Baris {$errorRowNumber}: no. telepon orang tua wajib diisi.",
            ];
        }

        $classValue = $this->extractField($row, self::FIELD_ALIASES['class_name']);
        $classId = $this->resolveClassId($classValue, $classLookup);

        if ($classId === null) {
            return [
                'data' => null,
                'error' => "Baris {$errorRowNumber}: kelas tidak terbaca/ tidak dikenali dari dokumen.",
            ];
        }

        if ($user->isClassScopedRole() && (int) $classId !== (int) $user->assigned_class_id) {
            return [
                'data' => null,
                'error' => "Baris {$errorRowNumber}: kelas tidak sesuai dengan akses akun Anda.",
            ];
        }

        $studentNumber = $this->extractField($row, self::FIELD_ALIASES['student_number']);
        if ($studentNumber === null) {
            $studentNumber = $this->generateStudentNumber();
            $generatedNumberCounter++;
            $generatedStudentNumber = true;
        } else {
            $generatedStudentNumber = false;
        }

        return [
            'data' => [
                'name' => $name,
                'student_number' => $studentNumber,
                'class_id' => $classId,
                'gender' => $this->normalizeGender($this->extractField($row, self::FIELD_ALIASES['gender'])),
                'phone' => $this->extractField($row, self::FIELD_ALIASES['phone']),
                'parent_phone' => $parentPhone,
                'address' => $this->extractField($row, self::FIELD_ALIASES['address']),
                'is_active' => true,
            ],
            'error' => null,
            'generated_student_number' => $generatedStudentNumber,
        ];
    }

    private function extractField(array $row, array $aliases): ?string
    {
        foreach ($aliases as $alias) {
            $normalizedAlias = $this->normalizeKey($alias);
            if (!array_key_exists($normalizedAlias, $row)) {
                continue;
            }

            $value = $this->sanitizeValue($row[$normalizedAlias]);
            if ($value !== null) {
                return $value;
            }
        }

        return null;
    }

    private function buildClassLookup(Collection $classes): array
    {
        $lookup = [
            'by_id' => [],
            'by_key' => [],
        ];

        foreach ($classes as $class) {
            $classId = (int) $class->id;
            $lookup['by_id'][$classId] = $classId;

            $keys = [
                $class->name,
                "{$class->grade} {$class->major}",
                "kelas {$class->grade} {$class->major}",
                "grade {$class->grade} {$class->major}",
                "{$class->major} {$class->grade}",
            ];

            $romanGrade = $this->gradeToRoman((string) $class->grade);
            if ($romanGrade !== null) {
                $keys[] = "{$romanGrade} {$class->major}";
                $keys[] = "kelas {$romanGrade} {$class->major}";
            }

            foreach ($keys as $key) {
                $normalized = $this->normalizeKey((string) $key);
                if ($normalized === '') {
                    continue;
                }

                if (!array_key_exists($normalized, $lookup['by_key'])) {
                    $lookup['by_key'][$normalized] = $classId;
                    continue;
                }

                if ($lookup['by_key'][$normalized] !== $classId) {
                    $lookup['by_key'][$normalized] = null;
                }
            }
        }

        return $lookup;
    }

    private function resolveClassId(?string $classValue, array $lookup): ?int
    {
        if ($classValue !== null && is_numeric($classValue)) {
            $numericClassId = (int) $classValue;
            if (isset($lookup['by_id'][$numericClassId])) {
                return $numericClassId;
            }
        }

        if ($classValue !== null) {
            $normalizedClass = $this->normalizeKey($classValue);

            if (
                $normalizedClass !== '' &&
                array_key_exists($normalizedClass, $lookup['by_key']) &&
                $lookup['by_key'][$normalizedClass] !== null
            ) {
                return $lookup['by_key'][$normalizedClass];
            }

            if ($normalizedClass !== '') {
                $matchedIds = [];
                foreach ($lookup['by_key'] as $classKey => $classId) {
                    if ($classId === null) {
                        continue;
                    }

                    if (str_contains($classKey, $normalizedClass) || str_contains($normalizedClass, $classKey)) {
                        $matchedIds[$classId] = true;
                    }
                }

                if (count($matchedIds) === 1) {
                    return (int) array_key_first($matchedIds);
                }
            }
        }

        return null;
    }

    private function normalizeGender(?string $gender): ?string
    {
        if ($gender === null) {
            return null;
        }

        $normalized = $this->normalizeKey($gender);

        if (in_array($normalized, ['l', 'lk', 'laki', 'laki laki', 'male', 'pria'], true)) {
            return 'Male';
        }

        if (in_array($normalized, ['p', 'pr', 'perempuan', 'female', 'wanita'], true)) {
            return 'Female';
        }

        return $gender;
    }

    private function isDuplicateStudentNumber(string $studentNumber, array $usedNumbersInBatch): bool
    {
        if (isset($usedNumbersInBatch[$studentNumber])) {
            return true;
        }

        return Student::query()->where('student_number', $studentNumber)->exists();
    }

    private function generateStudentNumber(array $usedNumbersInBatch = []): string
    {
        do {
            $generated = 'AUTO' . now()->format('ymd') . random_int(1000, 9999);
        } while (
            isset($usedNumbersInBatch[$generated]) ||
            Student::query()->where('student_number', $generated)->exists()
        );

        return $generated;
    }

    private function sanitizeValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $stringValue = trim((string) $value);
        if ($stringValue === '') {
            return null;
        }

        $stringValue = preg_replace('/\s+/u', ' ', $stringValue) ?? $stringValue;

        return $stringValue;
    }

    private function normalizeKey(string $value): string
    {
        $value = mb_strtolower(trim($value));
        $value = str_replace(['-', '_'], ' ', $value);
        $value = preg_replace('/[^a-z0-9 ]/u', ' ', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? trim($value);

        return $value;
    }

    private function gradeToRoman(string $grade): ?string
    {
        return match (trim($grade)) {
            '10' => 'x',
            '11' => 'xi',
            '12' => 'xii',
            default => null,
        };
    }
}
