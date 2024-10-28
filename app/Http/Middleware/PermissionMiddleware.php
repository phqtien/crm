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

        // Lấy role của user từ bảng user_roles
        $userRole = UserRole::where('user_id', $user->id)->first();

        // Lấy tất cả permission của role đó
        $rolePermissions = Role::with('permissions')
            ->find($userRole->role_id); // Lấy role dựa trên role_id

        $userPermissions = $rolePermissions->permissions->pluck('name')->toArray(); // Lấy tên của các permission

        // Kiểm tra nếu user có một trong những quyền cần thiết
        if (array_intersect($userPermissions, $permissions)) {
            return $next($request); // Cho phép truy cập nếu có quyền phù hợp
        }

        // Nếu không có quyền truy cập, chuyển hướng về trang chính với thông báo lỗi
        return redirect('/login')->with('error', 'Bạn không có quyền truy cập.');
    }
}
