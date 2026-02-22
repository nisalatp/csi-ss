<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'currentCompany']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        return Inertia::render('Admin/Users/Index', [
            'users' => $query->get()->map(function($user) {
                return [
                    'id' => $user->getRouteKey(),
                    'name' => $user->name,
                    'email' => $user->email,
                    'job_title' => $user->job_title,
                    'employee_id' => $user->employee_id,
                    'profile_photo_path' => $user->profile_photo_path,
                    'is_active' => $user->is_active,
                    'roles' => $user->roles->pluck('name'),
                    'company' => $user->currentCompany?->name,
                ];
            }),
            'filters' => $request->only(['search']),
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => [
                'id' => $user->getRouteKey(),
                'name' => $user->name,
                'email' => $user->email,
                'job_title' => $user->job_title,
                'employee_id' => $user->employee_id,
                'is_active' => $user->is_active,
                'roles' => $user->roles->pluck('name'),
                'current_company_id' => $user->current_company_id,
            ],
            'roles' => Role::where('name', '!=', 'Super Admin')->pluck('name'),
            'companies' => Company::all(['id', 'name']),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $input = $request->all();

        // Decode hashed IDs
        if ($request->filled('current_company_id')) {
            $decoded = \Vinkla\Hashids\Facades\Hashids::decode($request->current_company_id);
            if (!empty($decoded)) $input['current_company_id'] = $decoded[0];
        }

        $validated = \Illuminate\Support\Facades\Validator::make($input, [
            'job_title' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:50',
            'is_active' => 'required|boolean',
            'roles' => 'array',
            'current_company_id' => 'nullable|exists:companies,id',
        ])->validate();

        // Filter out Super Admin if it sneaks into roles
        if (isset($validated['roles'])) {
            $validated['roles'] = array_diff($validated['roles'], ['Super Admin']);
        }

        $user->update([
            'job_title' => $validated['job_title'],
            'employee_id' => $validated['employee_id'],
            'is_active' => $validated['is_active'],
            'current_company_id' => $validated['current_company_id'],
        ]);

        if (isset($validated['roles'])) {
            // Keep Super Admin if the user already has it (it's managed via hashes)
            if ($user->hasRole('Super Admin')) {
                $validated['roles'][] = 'Super Admin';
            }
            $user->syncRoles($validated['roles']);
        }

        return Redirect::route('admin.users.index')->with('success', 'User updated successfully');
    }
}
