<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation; // Wajib sama dengan password

    // Aturan Validasi
    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email', // Cek biar email gak dobel
        'password' => 'required|min:8|confirmed', // 'confirmed' otomatis cek field password_confirmation
    ];

    // Pesan Error Bahasa Indonesia (Opsional, biar ramah user)
    protected $messages = [
        'email.unique' => 'Email ini sudah terdaftar, silakan login.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'password.min' => 'Password minimal 8 karakter.',
    ];

    public function register()
    {
        $this->validate();

        // 1. Buat User Baru
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'customer', // Default role
            'avatar' => null, // Avatar kosong dulu kalau manual
        ]);

        // 2. Auto Login setelah daftar
        Auth::login($user);

        // 3. Redirect ke Home
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.register')->title('Daftar Akun - DN Auto');
    }
}