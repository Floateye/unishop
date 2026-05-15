<?php

namespace App\Http\Controllers;

use App\Enum\PermissionType;
use App\Enum\UserRole;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserManagementController extends Controller
{
    private function clearPermissionCache(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function requireSupervisor()
    {
        if (!auth()->user()->admin?->is_supervisor) {
            return redirect()->to(route('dashboard') . '#users')
                ->with('error', 'Only the supervisor can manage users.');
        }
        return null;
    }

    public function storeUser(Request $request)
    {
        Gate::authorize(PermissionType::DashboardView);

        if ($redirect = $this->requireSupervisor()) {
            return $redirect;
        }

        $validated = $request->validateWithBag('createUser', [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8'],
            'role'       => ['required', 'in:Admin,Customer'],
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
        ]);

        if ($validated['role'] === UserRole::Admin->value) {
            Admin::create(['user_id' => $user->id]);
            $user->syncRoles([UserRole::Admin->value]);
        } else {
            $user->syncRoles([UserRole::Customer->value]);
        }

        $this->clearPermissionCache();

        return redirect()->to(route('dashboard') . '#users')
            ->with('status', $user->first_name . ' ' . $user->last_name . ' has been created as ' . $validated['role'] . '.');
    }

    public function promote(User $user)
    {
        Gate::authorize(PermissionType::DashboardView);

        if ($redirect = $this->requireSupervisor()) {
            return $redirect;
        }

        if ($user->hasRole(UserRole::Admin->value)) {
            return redirect()->to(route('dashboard') . '#users')
                ->with('status', $user->first_name . ' is already an admin.');
        }

        if (!$user->admin) {
            Admin::create(['user_id' => $user->id]);
        }

        $user->syncRoles([UserRole::Admin->value]);
        $this->clearPermissionCache();

        return redirect()->to(route('dashboard') . '#users')
            ->with('status', $user->first_name . ' has been promoted to Admin.');
    }

    public function demote(User $user)
    {
        Gate::authorize(PermissionType::DashboardView);

        if ($redirect = $this->requireSupervisor()) {
            return $redirect;
        }

        if ($user->id === auth()->id()) {
            return redirect()->to(route('dashboard') . '#users')
                ->with('error', 'You cannot demote yourself.');
        }

        $user->admin?->delete();
        $user->syncRoles([UserRole::Customer->value]);
        $this->clearPermissionCache();

        return redirect()->to(route('dashboard') . '#users')
            ->with('status', $user->first_name . ' has been demoted to Customer.');
    }

    public function destroy(User $user)
    {
        Gate::authorize(PermissionType::DashboardView);

        if ($redirect = $this->requireSupervisor()) {
            return $redirect;
        }

        if ($user->id === auth()->id()) {
            return redirect()->to(route('dashboard') . '#users')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->admin?->delete();
        $user->delete();

        return redirect()->to(route('dashboard') . '#users')
            ->with('status', 'User deleted successfully.');
    }
}
