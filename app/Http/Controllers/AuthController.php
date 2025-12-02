<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Hash sınıfını kullandığımızdan emin olalım
use App\Http\Traits\RedirectsUsers; // Kendi yazdığımız Trait'i import ediyoruz

class AuthController extends Controller
{
    use RedirectsUsers; // Rol bazlı yönlendirme mantığımızı içeri alıyoruz

    /**
     * Login formunu gösterir.
     */
    public function showLoginForm()
    {
        // resources/views/auth/login.blade.php dosyasını çağırır
        return view('auth.login');
    }

    /**
     * Giriş işlemini yönetir.
     */
    public function login(Request $request)
    {
        // 1. Veri Doğrulama (Email ve Şifrenin doldurulmuş olması)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Kimlik Kontrolü
        // Auth::attempt metodu, şifreyi otomatik olarak veritabanındaki hash ile karşılaştırır.
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Oturum yenileme (Güvenlik için iyi bir pratiktir)
            $request->session()->regenerate();

            // 3. Yönlendirme: Başarılı olursa RedirectsUsers Trait'indeki metoda gider
            return redirect()->intended($this->redirectTo());
        }

        // Başarısız giriş durumunda
        return back()->withErrors([
            'email' => 'Girdiğiniz bilgiler kayıtlarımızla eşleşmiyor veya rolünüz uygun değil.',
        ])->onlyInput('email');
    }

    /**
     * Çıkış işlemini yönetir.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Çıkış sonrası kullanıcıyı login sayfasına yönlendir.
        return redirect()->route('login');
    }
    public function showRegisterForm()
    {
        return view('auth.register'); // Yeni oluşturduğumuz view'ı çağırır
    }

    /**
     * Yeni kullanıcı kaydını yönetir.
     */
    public function register(Request $request)
    {
        // 1. Veri Doğrulama
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'], // 'confirmed' kuralı, password_confirmation alanını gerektirir
        ]);

        // 2. Kullanıcıyı Oluşturma (Varsayılan rol: student)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // Otomatik kaydolan her kullanıcı 'student' rolüne sahip olur
        ]);

        // 3. Kullanıcıyı otomatik olarak giriş yaptırma (İsteğe bağlı)
        Auth::login($user);

        // 4. Rolüne göre dashboard'a yönlendirme
        return redirect($this->redirectTo());
    }
}
