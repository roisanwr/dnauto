<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Pegawai</h1>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
        <form wire:submit="update" class="space-y-6">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Pegawai</label>
                <input wire:model="nama_pegawai" type="text" class="w-full rounded-lg border-gray-300">
                @error('nama_pegawai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Jabatan</label>
                    <input wire:model="jabatan" type="text" class="w-full rounded-lg border-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Kontak (HP/WA)</label>
                    <input wire:model="kontak" type="text" class="w-full rounded-lg border-gray-300">
                    @error('kontak') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Status Ketersediaan</label>
                <select wire:model="status_ketersediaan" class="w-full rounded-lg border-gray-300">
                    <option value="available">Available (Siap Kerja)</option>
                    <option value="busy">Busy (Sedang Bertugas)</option>
                    <option value="cuti">Cuti / Off</option>
                </select>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('admin.pegawai') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg">Batal</a>
                <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-orange-600 rounded-lg hover:bg-orange-700">Simpan</button>
            </div>
        </form>
    </div>
</div>