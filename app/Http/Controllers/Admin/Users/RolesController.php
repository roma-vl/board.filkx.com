<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Services\Users\RoleService;
use App\Models\Users\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class RolesController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        if (Gate::denies('role')) {
            abort(403);
        }
        $this->roleService = $roleService;
    }

    public function index(): Response
    {
        $roles = Role::orderByDesc('created_at')->paginate(self::PER_PAGE);

        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'permissions' => $this->roleService->getAllPermissions(),
        ]);
    }

    public function edit(Role $role): JsonResponse
    {
        return response()->json($this->roleService->getRoleWithPermissions($role));
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $this->roleService->createRole($request->validated());

        return redirect()->route('admin.roles.index')->with('success', __('roles.created'));
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $this->roleService->updateRole($role, $request->validated());

        return redirect()->route('admin.roles.index')->with('success', __('roles.updated'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->roleService->deleteRole($role);

        return redirect()->route('admin.roles.index')->with('info', __('roles.deleted'));
    }
}
