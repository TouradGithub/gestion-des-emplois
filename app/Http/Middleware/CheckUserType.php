<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$userTypes): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userTypeId = auth()->user()->sys_types_user_id;

        // تحويل أسماء الأنواع إلى IDs
        $allowedTypeIds = [];
        foreach ($userTypes as $type) {
            if ($type === 'admin') {
                $allowedTypeIds[] = 1;
            } elseif ($type === 'teacher') {
                $allowedTypeIds[] = 2;
            }
        }

        if (!in_array($userTypeId, $allowedTypeIds)) {
            abort(403, 'غير مخول للوصول لهذه الصفحة');
        }

        return $next($request);
    }
}
