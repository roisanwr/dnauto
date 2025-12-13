<div class="space-y-6">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Pelanggan</h1>
            <p class="text-sm text-gray-500">Daftar pengguna terdaftar di aplikasi.</p>
        </div>
        {{-- Tidak ada tombol tambah, karena user register sendiri --}}
    </div>

    {{-- Tabel --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        
        {{-- Search Bar --}}
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <div class="relative max-w-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg sm:text-sm" placeholder="Cari nama atau email...">
            </div>
        </div>

        {{-- Table Body --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontak</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Akun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bergabung</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($customers as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Info User --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover border border-gray-200" 
                                             src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" 
                                             alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kontak --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->no_hp)
                                    <div class="flex items-center text-sm text-gray-700">
                                        <i data-lucide="phone" class="w-3 h-3 mr-2 text-gray-400"></i>
                                        {{ $user->no_hp }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum diisi</span>
                                @endif
                            </td>

                            {{-- Tipe Akun (Google/Manual) --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->google_id)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
                                        Google
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        <i data-lucide="mail" class="w-3 h-3 mr-1"></i>
                                        Email
                                    </span>
                                @endif
                            </td>

                            {{-- Tanggal Join --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="delete({{ $user->id }})" 
                                        wire:confirm="Yakin ingin menghapus pelanggan ini? Semua riwayat pesanan dia juga akan terhapus." 
                                        class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded transition-colors"
                                        title="Hapus Pelanggan">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada pelanggan terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $customers->links() }}
        </div>
    </div>
</div>