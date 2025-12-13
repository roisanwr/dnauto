<?php

namespace App\Livewire\Admin\Pelanggan;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Fitur Hapus User (Hati-hati, ini akan menghapus permanen)
    public function delete($id)
    {
        $user = User::findOrFail($id);
        
        // Proteksi: Jangan sampai Admin menghapus dirinya sendiri atau admin lain lewat menu ini
        if ($user->role === 'admin') {
            return;
        }

        $user->delete();
        session()->flash('message', 'Data pelanggan berhasil dihapus.');
    }

    public function render()
    {
        $customers = User::query()
            ->where('role', 'customer') // <--- Filter Wajib
            ->where(function($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.pelanggan.index', [
            'customers' => $customers
        ]);
    }
}