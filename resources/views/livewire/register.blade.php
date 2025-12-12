<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-pastel border border-stone-100 p-8">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-500 mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-stone-800">Buat Akun Baru</h2>
            <p class="text-stone-500 text-sm mt-1">Gabung sekarang untuk nikmati layanan kami.</p>
        </div>

        {{-- Form Register --}}
        <form wire:submit="register" class="space-y-4">
            
            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-sm font-bold text-stone-700 mb-1">Nama Lengkap</label>
                <input wire:model="name" type="text" class="w-full px-4 py-3 rounded-xl bg-stone-50 border-stone-200 focus:border-orange-400 focus:ring-orange-400 text-stone-800 transition-colors placeholder-stone-400" placeholder="Jhon Doe">
                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-bold text-stone-700 mb-1">Email Address</label>
                <input wire:model="email" type="email" class="w-full px-4 py-3 rounded-xl bg-stone-50 border-stone-200 focus:border-orange-400 focus:ring-orange-400 text-stone-800 transition-colors placeholder-stone-400" placeholder="nama@email.com">
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Password --}}
            <div x-data="{ show: false }" class="relative">
                <label class="block text-sm font-bold text-stone-700 mb-1">Password</label>
                <input wire:model="password" :type="show ? 'text' : 'password'" class="w-full px-4 py-3 rounded-xl bg-stone-50 border-stone-200 focus:border-orange-400 focus:ring-orange-400 text-stone-800 transition-colors placeholder-stone-400" placeholder="Minimal 8 karakter">
                <button type="button" @click.prevent="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.417M6.1 6.1A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.28 5.126M3 3l18 18"/></svg>
                </button>
                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div x-data="{ showConfirm: false }" class="relative">
                <label class="block text-sm font-bold text-stone-700 mb-1">Konfirmasi Password</label>
                <input wire:model="password_confirmation" :type="showConfirm ? 'text' : 'password'" class="w-full px-4 py-3 rounded-xl bg-stone-50 border-stone-200 focus:border-orange-400 focus:ring-orange-400 text-stone-800 transition-colors placeholder-stone-400" placeholder="Ulangi password">
                <button type="button" @click.prevent="showConfirm = !showConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-stone-600">
                    <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.223-3.417M6.1 6.1A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.28 5.126M3 3l18 18"/></svg>
                </button>
            </div>

            <button type="submit" class="w-full bg-stone-900 text-white font-bold py-3.5 rounded-xl hover:bg-stone-800 transition-colors shadow-lg shadow-orange-200/50 mt-4">
                Daftar Sekarang
            </button>
        </form>

        {{-- Link ke Login --}}
        <p class="text-center text-sm text-stone-500 mt-8">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-orange-500 font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>