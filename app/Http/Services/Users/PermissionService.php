<?php

namespace App\Http\Services\Users;

use App\Models\Users\Permission;
use Illuminate\Database\Eloquent\Collection;

class PermissionService
{
    public function getAll(): Collection
    {
        return Permission::orderBy('id', 'desc')->get();
    }

    public function create(array $data): Permission
    {
        return Permission::create($data);
    }

    public function update(Permission $permission, array $data): bool
    {
        return $permission->update($data);
    }

    public function delete(Permission $permission): ?bool
    {
        return $permission->delete();
    }
}
