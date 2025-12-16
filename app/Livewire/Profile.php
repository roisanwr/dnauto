<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Alamat;
use App\Models\User;

class Profile extends Component
{
    // --- DATA USER ---
    public $name;
    public $email;
    public $no_hp;
    // --- CHANGE PASSWORD ---
    public $new_password;
    public $new_password_confirmation;

    // --- DATA FORM ALAMAT ---
    public $showAlamatForm = false; // Toggle untuk buka/tutup form
    public $label_alamat;
    public $nama_penerima;
    public $no_hp_penerima;
    public $alamat_lengkap;
    
    // Logic Kota
    public $kota;          // Pilihan dari dropdown
    public $kota_manual;   // Inputan manual jika pilih "Lainnya"
    
    public $is_primary = false;

    // Daftar Kota untuk Dropdown (Sinkron dengan Checkout)
    public $pilihanKota = [
        'Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur',
        'Kota Bekasi', 'Kabupaten Bekasi', 'Cikarang',
        'Kota Depok', 'Kota Bogor', 'Kabupaten Bogor',
        'Kota Tangerang', 'Tangerang Selatan', 'Kabupaten Tangerang',
        'Lainnya (Luar Jabodetabek)'
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->no_hp = $user->no_hp;
    }

    // ==================================================
    // 1. UPDATE DATA DIRI (Nama & HP)
    // ==================================================
    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|numeric',
            'new_password' => 'nullable|min:8|confirmed',
        ],[
            'new_password.min' => 'Password harus minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $user = User::find(Auth::id());

        $user->update([
            'name' => $this->name,
            'no_hp' => $this->no_hp,
        ]);

        if (!empty($this->new_password)) {
            $user->password = Hash::make($this->new_password);
            $user->save();
            $this->reset(['new_password','new_password_confirmation']);
            session()->flash('password_message', 'Password berhasil disimpan.');
        }

        session()->flash('message', 'Profil berhasil diperbarui.');
    }

    

    // ==================================================
    // 2. MANAJEMEN ALAMAT
    // ==================================================

    public function toggleForm()
    {
        $this->showAlamatForm = !$this->showAlamatForm;
        $this->resetFormAlamat();
    }

    public function resetFormAlamat()
    {
        $this->label_alamat = '';
        $this->nama_penerima = Auth::user()->name; // Default nama sendiri
        $this->no_hp_penerima = Auth::user()->no_hp;
        $this->alamat_lengkap = '';
        $this->kota = '';
        $this->kota_manual = '';
        $this->is_primary = false;
    }

    public function simpanAlamat()
    {
        // Validasi Dasar
        $this->validate([
            'label_alamat' => 'required',
            'nama_penerima' => 'required',
            'no_hp_penerima' => 'required',
            'alamat_lengkap' => 'required',
            'kota' => 'required',
        ]);

        // Logic Penentuan Kota Final
        $kotaFinal = $this->kota;

        // Jika user pilih "Lainnya", wajib isi manual
        if ($this->kota == 'Lainnya (Luar Jabodetabek)') {
            $this->validate([
                'kota_manual' => 'required|string|min:3'
            ], [
                'kota_manual.required' => 'Silakan tulis nama Kota/Kabupaten tujuan.'
            ]);
            
            // Override nilai kota dengan inputan manual user
            $kotaFinal = $this->kota_manual;
        }

        // Cek apakah ini alamat pertama? Jika ya, otomatis Primary
        $count = Alamat::where('user_id', Auth::id())->count();
        if ($count == 0) {
            $this->is_primary = true;
        }

        // Kalau user centang Primary, yang lain harus jadi False
        if ($this->is_primary) {
            Alamat::where('user_id', Auth::id())->update(['is_primary' => false]);
        }

        // Simpan ke Database
        Alamat::create([
            'user_id' => Auth::id(),
            'label_alamat' => $this->label_alamat,
            'nama_penerima' => $this->nama_penerima,
            'no_hp_penerima' => $this->no_hp_penerima,
            'alamat_lengkap' => $this->alamat_lengkap,
            'kota' => $kotaFinal, // Simpan hasil final
            'is_primary' => $this->is_primary
        ]);

        session()->flash('alamat_message', 'Alamat baru berhasil disimpan.');
        $this->toggleForm(); // Tutup form
    }

    public function hapusAlamat($id)
    {
        $alamat = Alamat::where('user_id', Auth::id())->where('id', $id)->first();
        if ($alamat) {
            $wasPrimary = $alamat->is_primary;
            $alamat->delete();
            
            // Kalau yang dihapus adalah alamat utama, oper status utama ke alamat lain (jika ada)
            if ($wasPrimary) {
                $next = Alamat::where('user_id', Auth::id())->first();
                if ($next) {
                    $next->update(['is_primary' => true]);
                }
            }
        }
    }

    public function setUtama($id)
    {
        // Reset semua alamat user ini jadi bukan utama
        Alamat::where('user_id', Auth::id())->update(['is_primary' => false]);
        
        // Set alamat yang dipilih jadi utama
        Alamat::where('user_id', Auth::id())->where('id', $id)->update(['is_primary' => true]);
    }

    public function render()
    {
        return view('livewire.profile', [
            // Urutkan biar yang Utama selalu paling atas
            'daftarAlamat' => Alamat::where('user_id', Auth::id())
                                    ->orderBy('is_primary', 'desc')
                                    ->latest()
                                    ->get()
        ]);
    }
}