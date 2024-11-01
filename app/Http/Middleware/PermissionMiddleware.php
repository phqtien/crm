<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole; // Nhớ import model UserRole
use App\Models\Role; // Import model Role

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = Auth::user();

        $userRole = UserRole::where('user_id', $user->id)->first();

        $rolePermissions = Role::with('permissions')
            ->find($userRole->role_id);

        $userPermissions = $rolePermissions->permissions->pluck('name')->toArray();

        if (array_intersect($userPermissions, $permissions)) {
            return $next($request);
        }

        return redirect('/home')->with('error', "You don't have permission to access!");
    }
}
