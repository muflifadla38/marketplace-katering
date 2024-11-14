<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        $allowAccess = false;

        if (auth()->check()) {
            if ($request->user()->role_id == $role) {
                $allowAccess = true;
            }
        }

        if (! $allowAccess) {
            return redirect()->back()->with([
                'alert' => 'danger',
                'message' => 'You do not have access to this page',
            ]);
        }

        return $next($request);
    }
}
