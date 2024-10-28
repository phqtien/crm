<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'role_permissions';
    protected $fillable = ['role_id', 'permission_id'];

    /**
     * Belongs to a role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Belongs to a permission.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
