<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StudentBulkImportService;
use Illuminate\Http\Request;

class StudentManagementController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = \App\Models\Student::with('schoolClass');

        if ($user->isClassScopedRole()) {
            $this->ensureClassScopedUserHasAssignedClass($user);
            $query->where('class_id', $user->assigned_class_id);
        }

        $students = $query->paginate(20);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $this->ensureCanManageStudents();
        $user = auth()->user();

        if ($user->isClassScopedRole()) {
            $this->ensureClassScopedUserHasAssignedClass($user);
            $classes = \App\Models\SchoolClass::active()->where('id', $user->assigned_class_id)->get();
        } else {
            $classes = \App\Models\SchoolClass::active()->get();
        }

        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $this->ensureCanManageStudents();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_number' => 'required|string|unique:students,student_number',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'nullable|string',
            'phone' => 'nullable|string',
            'parent_phone' => 'required|string|max:50',
            'address' => 'nullable|string',
        ]);

        $user = auth()->user();

        if ($user->isClassScopedRole()) {
            $this->ensureClassScopedUserHasAssignedClass($user);

            if ((int) $validated['class_id'] !== (int) $user->assigned_class_id) {
                abort(403, 'Anda hanya bisa mengelola siswa kelas Anda sendiri.');
            }
        }

        \App\Models\Student::create($validated);
        return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
    }

    public function import(Request $request, StudentBulkImportService $importService)
    {
        $this->ensureCanManageStudents();

        $validated = $request->validate([
            'import_file' => 'required|file|mimes:csv,xlsx,pdf|max:10240',
        ]);

        $user = auth()->user();

        if ($user->isClassScopedRole()) {
            $this->ensureClassScopedUserHasAssignedClass($user);
        }

        $result = $importService->import(
            file: $validated['import_file'],
            user: $user
        );

        $summary = "Import selesai: {$result['imported_rows']} data berhasil, {$result['skipped_rows']} data dilewati.";

        return redirect()
            ->route('admin.students.index')
            ->with('success', $summary)
            ->with('import_summary', $result);
    }

    public function downloadImportTemplate()
    {
        $this->ensureCanManageStudents();

        $headers = ['nama', 'nis', 'kelas', 'jenis_kelamin', 'no_hp_siswa', 'no_hp_ortu', 'alamat'];
        $example = ['Contoh Siswa', '20260001', 'Grade 10 PPLG', 'L', '081234567890', '081298765432', 'Jl. Contoh No. 1'];

        $lines = [
            implode(',', $headers),
            implode(',', $example),
        ];

        $content = "\xEF\xBB\xBF" . implode("\n", $lines);
        $filename = 'template-import-siswa.csv';

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function edit($id)
    {
        $this->ensureCanManageStudents();
        $student = \App\Models\Student::findOrFail($id);
        $user = auth()->user();

        if ($user->isClassScopedRole()) {
            $this->ensureClassScopedUserHasAssignedClass($user);

            if ((int) $student->class_id !== (int) $user->assigned_class_id) {
                abort(403, 'Anda hanya bisa mengelola siswa kelas Anda sendiri.');
            }

            $classes = \App\Models\SchoolClass::active()->where('id', $user->assigned_class_id)->get();
        } else {
            $classes = \App\Models\SchoolClass::active()->get();
        }

        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $this->ensureCanManageStudents();
        $student = \App\Models\Student::findOrFail($id);
        $user = auth()->user();

        if ($user->isClassScopedRole()) {
            $this->ensureClassScopedUserHasAssignedClass($user);

            if ((int) $student->class_id !== (int) $user->assigned_class_id) {
                abort(403, 'Anda hanya bisa mengelola siswa kelas Anda sendiri.');
            }
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_number' => 'required|string|unique:students,student_number,' . $id,
            'class_id' => 'required|exists:classes,id',
            'gender' => 'nullable|string',
            'phone' => 'nullable|string',
            'parent_phone' => 'required|string|max:50',
            'address' => 'nullable|string',
        ]);

        if ($user->isClassScopedRole() && (int) $validated['class_id'] !== (int) $user->assigned_class_id) {
            abort(403, 'Anda hanya bisa mengelola siswa kelas Anda sendiri.');
        }

        $student->update($validated);
        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $this->ensureCanManageStudents();
        $student = \App\Models\Student::findOrFail($id);
        $user = auth()->user();

        if ($user->isClassScopedRole()) {
            $this->ensureClassScopedUserHasAssignedClass($user);

            if ((int) $student->class_id !== (int) $user->assigned_class_id) {
                abort(403, 'Anda hanya bisa mengelola siswa kelas Anda sendiri.');
            }
        }

        $student->delete();
        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }

    private function ensureClassScopedUserHasAssignedClass($user): void
    {
        if (!$user->hasAssignedClass()) {
            abort(403, 'Akun Anda belum terhubung ke kelas. Hubungi admin.');
        }
    }

    private function ensureCanManageStudents(): void
    {
        if (auth()->user()->isWalas()) {
            abort(403, 'Akun walas hanya memiliki akses lihat data siswa.');
        }
    }
}
