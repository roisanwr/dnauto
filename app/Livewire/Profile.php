<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads; // Wajib buat upload

class Profile extends Component
{
    use WithFileUploads;

    public $name, $email, $no_hp;
    public $photo; // Buat file upload sementara
    public $new_password; // Buat set/ganti password

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->no_hp = $user->no_hp;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|min:3',
            'photo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $user = Auth::user();
        $data = ['name' => $this->name, 'no_hp' => $this->no_hp];

        // Logika Ganti Avatar
        if ($this->photo) {
            // Hapus foto lama jika bukan dari Google (disimpan di storage)
            if ($user->avatar && str_contains($user->avatar, 'storage')) {
                // Logic hapus file lama bisa ditambah disini
            }
            
            // Simpan foto baru ke folder public/avatars
            $path = $this->photo->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $path;
        }

        // Logika Set Password
        if (!empty($this->new_password)) {
            $this->validate(['new_password' => 'min:8']);
            $data['password'] = Hash::make($this->new_password);
        }

        $user->update($data);
        
        // Refresh halaman biar foto nampil
        $this->redirect('/profile', navigate: true);
        session()->flash('message', 'Profil berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.profile')->title('Kelola Profil');
    }
}