<?php
// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Gelen isteği işler.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Eğer kullanıcı giriş yapmamışsa veya rolü uyuşmuyorsa

            // Eğer AJAX isteği değilse, yetkisiz erişim mesajı gösterebiliriz.
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
            // Veya kullanıcıyı ana sayfaya/login sayfasına yönlendir
            return redirect('/')->with('error', 'Bu sayfaya erişim yetkiniz yoktur.');
        }

        return $next($request);
    }
}
