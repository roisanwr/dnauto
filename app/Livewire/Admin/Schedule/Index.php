<?php

namespace App\Livewire\Admin\Schedule;

use App\Models\Schedule;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public $filterTanggal;

    public function mount()
    {
        // KOSONGKAN DEFAULTNYA
        // Supaya logic "Range Waktu" di render() jalan
        $this->filterTanggal = null;
    }

    public function render()
    {
        $jadwals = Schedule::with(['pesanan', 'pegawai'])
            // LOGIC BARU:
            ->when($this->filterTanggal, function($q) {
                // 1. Jika User pilih tanggal, filter spesifik
                $q->whereDate('tgl_pengerjaan', $this->filterTanggal);
            }, function($q) {
                // 2. Jika KOSONG (Default), tampilkan range:
                // 1 Minggu Lalu s/d 2 Minggu Kedepan
                $q->whereBetween('tgl_pengerjaan', [
                    now()->subWeek()->startOfDay(),   // 7 hari lalu
                    now()->addWeeks(2)->endOfDay()    // 14 hari ke depan
                ]);
            })
            // Urutkan jadwal yang paling dekat dengan hari ini
            ->orderBy('tgl_pengerjaan', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->paginate(10);

        return view('livewire.admin.schedule.index', [
            'jadwals' => $jadwals
        ]);
    }
}