<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $fillable = ['role_id', 'object', 'operation'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
