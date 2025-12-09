<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-pastel border border-stone-100 p-8">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-500 mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-stone-800">Selamat Datang Kembali!</h2>
            <p class="text-stone-500 text-sm mt-1">Silakan masuk untuk melanjutkan.</p>
        </div>

        <a href="{{ route('google.login') }}" class="flex items-center justify-center w-full bg-white border border-stone-200 text-stone-700 font-bold py-3 rounded-xl hover:bg-stone-50 transition-colors shadow-sm mb-6 group">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6 mr-3 group-hover:scale-110 transition-transform" alt="Google">
            Masuk dengan Google
        </a>

        <div class="relative flex py-2 items-center mb-6">
            <div class="flex-grow border-t border-stone-200"></div>
            <span class="flex-shrink mx-4 text-stone-400 text-xs">ATAU LOGIN MANUAL</span>
            <div class="flex-grow border-t border-stone-200"></div>
        </div>

        <form wire:submit="login" class="space-y-4">
            
            <div>
                <label class="block text-sm font-bold text-stone-700 mb-1">Email Address</label>
                <input wire:model="email" type="email" class="w-full px-4 py-3 rounded-xl bg-stone-50 border-stone-200 focus:border-orange-400 focus:ring-orange-400 text-stone-800 transition-colors placeholder-stone-400" placeholder="nama@email.com">
                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-stone-700 mb-1">Password</label>
                <input wire:model="password" type="password" class="w-full px-4 py-3 rounded-xl bg-stone-50 border-stone-200 focus:border-orange-400 focus:ring-orange-400 text-stone-800 transition-colors placeholder-stone-400" placeholder="••••••••">
                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-stone-900 text-white font-bold py-3.5 rounded-xl hover:bg-stone-800 transition-colors shadow-lg shadow-orange-200/50 mt-4">
                Masuk Sekarang
            </button>
        </form>

        <p class="text-center text-sm text-stone-500 mt-8">
            Belum punya akun? 
            <a href="#" class="text-orange-500 font-bold hover:underline">Daftar di sini</a>
        </p>
    </div>
</div>