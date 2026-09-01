<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Shop owner has full access
        if ($user->shop) {
            return $next($request);
        }

        // Check employee role
        $employee = $user->employee;

        if (!$employee || !$employee->is_active) {
            abort(403, 'غير مصرح لك بالدخول. تواصل مع مدير المتجر.');
        }

        if (!in_array($employee->role, $roles)) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذه الصفحة.');
        }

        // Store employee info in request for later use
        $request->attributes->set('employee', $employee);
        $request->attributes->set('employeeRole', $employee->role);

        return $next($request);
    }
}
