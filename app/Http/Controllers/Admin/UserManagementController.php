<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->with('assignedClass')
            ->where('role', 'walas')
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $walasUsers = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('walasUsers'));
    }

    public function create()
    {
        $classes = SchoolClass::active()
            ->whereNotIn('id', User::where('role', 'walas')->whereNotNull('assigned_class_id')->pluck('assigned_class_id'))
            ->orderBy('name')
            ->get();

        return view('admin.users.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'assigned_class_id' => [
                'required',
                'exists:classes,id',
                Rule::unique('users', 'assigned_class_id')->where(fn ($query) => $query->where('role', 'walas')),
            ],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'walas',
            'assigned_class_id' => $validated['assigned_class_id'],
            'whatsapp_number' => $validated['whatsapp_number'] ?: null,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun walas berhasil dibuat.');
    }

    public function show(User $user)
    {
        $this->ensureWalasUser($user);

        return redirect()->route('admin.users.edit', $user);
    }

    public function edit(User $user)
    {
        $this->ensureWalasUser($user);

        $classes = SchoolClass::active()
            ->where(function ($query) use ($user) {
                $query->where('id', $user->assigned_class_id)
                    ->orWhereNotIn('id', User::where('role', 'walas')->where('id', '!=', $user->id)->whereNotNull('assigned_class_id')->pluck('assigned_class_id'));
            })
            ->orderBy('name')
            ->get();

        return view('admin.users.edit', compact('user', 'classes'));
    }

    public function update(Request $request, User $user)
    {
        $this->ensureWalasUser($user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'assigned_class_id' => [
                'required',
                'exists:classes,id',
                Rule::unique('users', 'assigned_class_id')
                    ->where(fn ($query) => $query->where('role', 'walas'))
                    ->ignore($user->id),
            ],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'assigned_class_id' => $validated['assigned_class_id'],
            'whatsapp_number' => $validated['whatsapp_number'] ?: null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun walas berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->ensureWalasUser($user);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun walas berhasil dihapus.');
    }

    private function ensureWalasUser(User $user): void
    {
        if (!$user->isWalas()) {
            abort(404);
        }
    }
}
