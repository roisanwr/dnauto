<div class="max-w-4xl mx-auto py-10 px-4">
    
    <div class="bg-white rounded-3xl shadow-pastel border border-stone-100 overflow-hidden">
        
        <div class="md:flex">
            {{-- Bagian Kiri: Avatar & Info Dasar --}}
            <div class="md:w-1/3 bg-orange-50 p-8 text-center border-r border-orange-100">
                <div class="relative inline-block group">
                    {{-- Preview Gambar (Baru atau Lama) --}}
                    @if ($photo) 
                        <img src="{{ $photo->temporaryUrl() }}" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md mx-auto">
                    @else
                        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($name) }}" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-md mx-auto">
                    @endif

                    {{-- Tombol Upload Overlay --}}
                    <label class="absolute bottom-0 right-0 bg-stone-800 text-white p-2 rounded-full cursor-pointer hover:bg-orange-500 transition-colors shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <input wire:model="photo" type="file" class="hidden">
                    </label>
                </div>

                <h2 class="mt-4 font-bold text-xl text-stone-800">{{ $name }}</h2>
                <p class="text-sm text-stone-500">{{ $email }}</p>

                {{-- Status Google Account --}}
                <div class="mt-6">
                    @if(Auth::user()->google_id)
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            Terhubung Google
                        </div>
                    @else
                        <a href="{{ route('google.login') }}" class="inline-flex items-center px-4 py-2 bg-white border border-stone-300 rounded-xl text-xs font-bold text-stone-700 shadow-sm hover:bg-stone-50 transition-colors">
                            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4 mr-2">
                            Hubungkan Google
                        </a>
                    @endif
                </div>
            </div>

            {{-- Bagian Kanan: Form Edit --}}
            <div class="md:w-2/3 p-8">
                <h3 class="text-lg font-bold text-stone-800 mb-6 pb-2 border-b border-stone-100">Edit Informasi</h3>
                
                <form wire:submit="updateProfile" class="space-y-5">
                    
                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-bold text-stone-700 mb-1">Nama Lengkap</label>
                        <input wire:model="name" type="text" class="w-full px-4 py-2 rounded-xl bg-stone-50 border-stone-200 focus:border-orange-400 focus:ring-orange-400 text-stone-800">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label class="block text-sm font-bold text-stone-700 mb-1">Nomor WhatsApp / HP</label>
                        <input wire:model="no_hp" type="text" class="w-full px-4 py-2 rounded-xl bg-stone-50 border-stone-200 focus:border-orange-400 focus:ring-orange-400 text-stone-800" placeholder="08...">
                    </div>

                    {{-- Password Baru --}}
                    <div class="pt-4 border-t border-stone-50 mt-4">
                        <label class="block text-sm font-bold text-stone-700 mb-1">
                            {{ Auth::user()->password ? 'Ganti Password' : 'Buat Password Baru' }}
                        </label>
                        <input wire:model="new_password" type="password" class="w-full px-4 py-2 rounded-xl bg-stone-50 border-stone-200 focus:border-orange-400 focus:ring-orange-400 text-stone-800" placeholder="Kosongkan jika tidak ingin mengubah">
                        @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <p class="text-xs text-stone-400 mt-1">Minimal 8 karakter.</p>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="bg-stone-900 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-orange-200/50 hover:bg-stone-800 transition-all active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>