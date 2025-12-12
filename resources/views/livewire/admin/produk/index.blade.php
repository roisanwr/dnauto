<div class="space-y-6">
    
    {{-- Header Halaman --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Produk & Layanan</h1>
            <p class="text-sm text-gray-500">Kelola katalog barang dan jasa bengkel.</p>
        </div>
        
        <a href="{{ route('admin.produk.create') }}" wire:navigate class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-500 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Tambah Produk
        </a>
    </div>

    {{-- Tabel Content --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        
        {{-- Toolbar (Search) --}}
        <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div class="relative max-w-sm w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition duration-150 ease-in-out" 
                       placeholder="Cari nama produk...">
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimasi PO</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Aksi</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($produks as $produk)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Kolom Produk (Gambar + Nama) --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        @if($produk->gambar)
                                            <img class="h-10 w-10 rounded-lg object-cover border border-gray-200" src="{{ asset('storage/' . $produk->gambar) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200">
                                                <i data-lucide="image" class="w-5 h-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $produk->nama_produk }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($produk->deskripsi, 30) }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Kategori --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $produk->kategori }}
                                </span>
                            </td>

                            {{-- Harga --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </td>

                            {{-- Estimasi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="clock" class="w-3.5 h-3.5 text-orange-500"></i>
                                    {{ $produk->estimasi_hari_kerja }} Hari
                                </div>
                            </td>

                            {{-- Tombol Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    
                                    {{-- TOMBOL EDIT --}}
                                    <a href="{{ route('admin.produk.edit', $produk->id) }}" wire:navigate 
                                    class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded transition-colors"
                                    title="Edit Produk">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>

                                    {{-- TOMBOL HAPUS (Dengan Konfirmasi) --}}
                                    <button wire:click="delete({{ $produk->id }})"
                                            wire:confirm="Yakin ingin menghapus produk '{{ $produk->nama_produk }}'?"
                                            class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded transition-colors"
                                            title="Hapus Produk">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- Tampilan Kosong --}}
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 p-3 rounded-full mb-3">
                                        <i data-lucide="package-open" class="w-6 h-6 text-gray-400"></i>
                                    </div>
                                    <p class="text-base font-medium text-gray-900">Belum ada produk</p>
                                    <p class="text-sm text-gray-400 mt-1">Mulai tambahkan produk jualanmu sekarang.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $produks->links() }}
        </div>
    </div>
</div>