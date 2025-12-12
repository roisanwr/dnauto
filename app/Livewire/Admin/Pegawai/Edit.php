<?php

namespace App\Livewire\Admin\Pegawai;

use App\Models\Pegawai;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Edit extends Component
{
    public $pegawaiId, $nama_pegawai, $jabatan, $kontak, $status_ketersediaan;

    public function mount($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $this->pegawaiId = $pegawai->id;
        $this->nama_pegawai = $pegawai->nama_pegawai;
        $this->jabatan = $pegawai->jabatan;
        $this->kontak = $pegawai->kontak;
        $this->status_ketersediaan = $pegawai->status_ketersediaan;
    }

    public function update()
    {
        $this->validate([
            'nama_pegawai' => 'required|min:3',
            'jabatan' => 'required',
            'kontak' => 'required',
            'status_ketersediaan' => 'required',
        ]);

        $pegawai = Pegawai::findOrFail($this->pegawaiId);
        $pegawai->update([
            'nama_pegawai' => $this->nama_pegawai,
            'jabatan' => $this->jabatan,
            'kontak' => $this->kontak,
            'status_ketersediaan' => $this->status_ketersediaan,
        ]);

        session()->flash('message', 'Data pegawai diperbarui!');
        return redirect()->route('admin.pegawai');
    }

    public function render()
    {
        return view('livewire.admin.pegawai.edit');
    }
}