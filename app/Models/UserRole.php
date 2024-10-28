<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_roles';
    protected $fillable = ['user_id', 'role_id'];

    /**
     * Belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Belongs to a role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
