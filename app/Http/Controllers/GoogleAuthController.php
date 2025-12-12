<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    // 1. Arahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Google mengembalikan data user ke sini
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // KONDISI 1: User sedang login (Ingin menghubungkan akun)
            if (Auth::check()) {
                $currentUser = Auth::user();
                
                // Update data user saat ini dengan Google ID
                $currentUser->update([
                    'google_id' => $googleUser->getId(),
                    // Update avatar hanya jika user belum punya avatar
                    'avatar' => $currentUser->avatar ?? $googleUser->getAvatar(), 
                ]);

                return redirect('/profile')->with('message', 'Akun Google berhasil dihubungkan!');
            }

            // KONDISI 2: User belum login (Login/Register biasa)
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Register User Baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => null,
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]);
            } else {
                // User Lama Login (Update ID & Avatar jika kosong)
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $user->avatar ?? $googleUser->getAvatar(),
                ]);
            }

            Auth::login($user);

            // LOGIKA BARU: Cek Role
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            // Kalau customer, ke Home
            return redirect('/');
        
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login/link Google.');
        }
    }
    // 3. Fungsi Logout (Tambahan Baru)
    public function logout(Request $request)
    {
        // 1. Logout user dari auth
        Auth::logout();
 
        // 2. Invalidate session (Security Best Practice)
        // Ini penting biar sesi lama user benar-benar hangus
        $request->session()->invalidate();
 
        // 3. Regenerate CSRF Token
        // Mencegah serangan CSRF pada sesi berikutnya
        $request->session()->regenerateToken();
 
        // 4. Balikin ke halaman utama
        return redirect('/');
    }
}