<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return Inertia::render('roles-permissions/Index', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function edit(Request $request)
    {
        $roleId = $request->query('role_id');
        $role = Role::with('permissions')->findOrFail($roleId);
        $permissions = Permission::all();

        return Inertia::render('roles-permissions/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::findOrFail($data['role_id']);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('roles.permissions.index')->with('success', 'Permissions updated successfully.');
    }
}
