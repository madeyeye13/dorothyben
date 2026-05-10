<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && ($user->hasRole('admin') || $user->hasRole('super-admin'))) {
            return $next($request);
        }

        // Scanner role — only scanner and guest-lookup allowed
        return redirect()->route('admin.scan');
    }
}