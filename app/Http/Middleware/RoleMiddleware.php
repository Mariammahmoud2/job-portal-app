<?php

namespace App\Http\Middleware; // تأكدي أن الـ Namespace مكتوب صح

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware // تأكدي أن اسم الكلاس مطابق لاسم الملف
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (auth()->check()) {
            $role = auth()->user()->role;
            if (!in_array($role, $roles)) {
                abort(403);
            }
        } else {
            return redirect()->route('login');
        }

        return $next($request);
    }
}