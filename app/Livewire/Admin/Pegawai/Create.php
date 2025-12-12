<?php

namespace App\Livewire\Admin\Pegawai;

use App\Models\Pegawai;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Create extends Component
{
    public $nama_pegawai, $jabatan = 'Teknisi', $kontak, $status_ketersediaan = 'available';

    protected $rules = [
        'nama_pegawai' => 'required|min:3',
        'jabatan' => 'required',
        'kontak' => 'required',
        'status_ketersediaan' => 'required',
    ];

    public function store()
    {
        $this->validate();

        Pegawai::create([
            'nama_pegawai' => $this->nama_pegawai,
            'jabatan' => $this->jabatan,
            'kontak' => $this->kontak,
            'status_ketersediaan' => $this->status_ketersediaan,
        ]);

        session()->flash('message', 'Pegawai berhasil ditambahkan!');
        return redirect()->route('admin.pegawai');
    }

    public function render()
    {
        return view('livewire.admin.pegawai.create');
    }
}