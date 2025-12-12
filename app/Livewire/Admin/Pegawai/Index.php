<?php

namespace App\Livewire\Admin\Pegawai;

use App\Models\Pegawai;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        Pegawai::find($id)->delete();
        session()->flash('message', 'Pegawai berhasil dihapus.');
    }

    public function render()
    {
        $pegawais = Pegawai::query()
            ->where('nama_pegawai', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.pegawai.index', [
            'pegawais' => $pegawais
        ]);
    }
}