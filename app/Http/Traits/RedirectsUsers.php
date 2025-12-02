<?php
// app/Http/Traits/RedirectsUsers.php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;

trait RedirectsUsers
{
    /**
     * Kullanıcının rolüne göre yönlendirilecek URL'i döndürür.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $role = Auth::user()->role; // Unutmayın: users tablosuna 'role' alanı ekledik

        switch ($role) {
            case 'admin':
                return route('admin.dashboard');
                break;
            case 'staff':
                return route('staff.dashboard');
                break;
            case 'student':
                return route('student.dashboard'); // Veya direkt '/dashboard'
                break;
            default:
                // Tanımsız bir rol gelirse varsayılan olarak ana sayfaya yönlendir
                return '/';
                break;
        }
    }
}
