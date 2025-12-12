<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout; // <--- Import ini
use Livewire\Component;

// Tempel atribut ini untuk ganti layout:
#[Layout('components.layouts.admin')] 
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}