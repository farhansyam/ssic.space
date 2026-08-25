<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $members = User::with('division')
            ->withCount('userBadges')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q').'%';
                $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term));
            })
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->string('role')))
            ->when($request->filled('division_id'), fn ($query) => $query->where('division_id', $request->integer('division_id')))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $divisions = Division::orderBy('name')->get();

        $stats = [
            'total' => User::count(),
            'members' => User::where('role', 'member')->count(),
            'admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
        ];

        return view('admin.members.index', compact('members', 'divisions', 'stats'));
    }

    public function create(): View
    {
        $divisions = Division::orderBy('name')->get();

        return view('admin.members.create', compact('divisions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'division_id' => $validated['division_id'] ?? null,
            'role' => $this->resolveRole($request, $validated['role']),
            'bio' => $validated['bio'] ?? null,
        ]);

        if ($request->hasFile('avatar')) {
            $user->update(['avatar' => $request->file('avatar')->store('avatars', 'public')]);
        }

        return redirect()->route('admin.members.index')->with('success', 'Akun "'.$user->name.'" berhasil dibuat.');
    }

    public function edit(User $member): View
    {
        $divisions = Division::orderBy('name')->get();

        return view('admin.members.edit', ['member' => $member, 'divisions' => $divisions]);
    }

    public function update(Request $request, User $member): RedirectResponse
    {
        $validated = $this->validated($request, $member->id);

        $member->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'division_id' => $validated['division_id'] ?? null,
            'role' => $this->resolveRole($request, $validated['role'], $member),
            'bio' => $validated['bio'] ?? null,
        ]);

        if (! empty($validated['password'])) {
            $member->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $member->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $member->save();

        return redirect()->route('admin.members.index')->with('success', 'Akun "'.$member->name.'" berhasil diperbarui.');
    }

    public function destroy(Request $request, User $member): RedirectResponse
    {
        if ($member->id === $request->user()->id) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        if ($member->isSuperAdmin() && ! $request->user()->isSuperAdmin()) {
            return back()->with('error', 'Hanya Super Admin yang bisa menghapus akun Super Admin.');
        }

        $name = $member->name;
        $member->delete();

        return redirect()->route('admin.members.index')->with('success', 'Akun "'.$name.'" berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'.($ignoreId ? ','.$ignoreId : '')],
            'phone' => ['nullable', 'string', 'max:20'],
            'division_id' => ['nullable', 'exists:divisions,id'],
            'role' => ['required', 'in:member,admin,super_admin'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];

        $rules['password'] = $ignoreId
            ? ['nullable', 'string', 'min:8', 'confirmed']
            : ['required', 'string', 'min:8', 'confirmed'];

        return $request->validate($rules);
    }

    private function resolveRole(Request $request, string $requestedRole, ?User $target = null): string
    {
        if (! $request->user()->isSuperAdmin()) {
            return $target?->role ?? 'member';
        }

        return $requestedRole;
    }
}
