<?php

namespace Database\Factories;

use App\Models\Users\Permission;
use App\Models\Users\Role;
use App\Models\Users\RolePermission;
use Illuminate\Database\Eloquent\Factories\Factory;

class RolePermissionFactory extends Factory
{
    protected $model = RolePermission::class;

    public function definition()
    {
        return [
            'role_id' => Role::factory(),
            'permission_id' => Permission::factory(),
        ];
    }
}
