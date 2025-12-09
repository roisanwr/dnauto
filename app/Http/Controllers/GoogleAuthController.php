<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
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

            // Cek apakah user dengan email ini sudah ada?
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Kalo belum ada, buat user baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => null, // Tidak butuh password
                    'role' => 'customer', // Default role
                    'email_verified_at' => now(), // Anggap email Google sudah valid
                ]);
            } else {
                // Kalo sudah ada, update google_id dan avatar jika belum ada
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            // Login manual user tersebut
            Auth::login($user);

            // Redirect ke Home
            return redirect('/');
        
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google. Coba lagi.');
        }
    }
}