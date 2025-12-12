<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email;
    public $password;

public function login()
    {
        // ... validasi ...
        
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            
            // CEK ROLE DISINI
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

        // ... error handling ...
    }

    public function render()
    {
        return view('livewire.login');
    }
}