<div class="max-w-7xl mx-auto space-y-8">
                
    <div>
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Workshop Overview</h1>
        <p class="text-sm text-gray-500 mt-1">Update harian dan performa bengkel hari ini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-gray-300 transition-colors cursor-default">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Mobil Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">12</p>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i data-lucide="car-front" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="text-green-600 font-medium flex items-center gap-1">
                    <i data-lucide="arrow-up" class="w-3 h-3"></i> 12%
                </span>
                <span class="text-gray-400 ml-1">vs kemarin</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-gray-300 transition-colors cursor-default">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">Rp 4.2M</p>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i data-lucide="banknote" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="text-green-600 font-medium flex items-center gap-1">
                    <i data-lucide="arrow-up" class="w-3 h-3"></i> 8%
                </span>
                <span class="text-gray-400 ml-1">vs kemarin</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-gray-300 transition-colors cursor-default">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Menunggu Sparepart</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">3</p>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <i data-lucide="box" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="text-red-500 font-medium flex items-center gap-1">
                    <i data-lucide="arrow-up" class="w-3 h-3"></i> 2
                </span>
                <span class="text-gray-400 ml-1">pesanan urgent</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-gray-300 transition-colors cursor-default">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-500">Selesai Dikerjakan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">5</p>
                </div>
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs">
                <span class="text-gray-500 font-medium">
                    Siap diambil customer
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-white">
            <h2 class="text-base font-semibold text-gray-900">Antrean Servis Saat Ini</h2>
            <div class="flex gap-2">
                <button class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <i data-lucide="filter" class="w-3 h-3"></i> Filter
                </button>
                <button class="px-3 py-1.5 text-xs font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition-colors flex items-center gap-2">
                    <i data-lucide="plus" class="w-3 h-3"></i> Entri Baru
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Polisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mekanik</th>
                        <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">B 1234 CD</div>
                            <div class="text-xs text-gray-400">Masuk: 08:30</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Honda Civic Turbo</div>
                            <div class="text-xs text-gray-500">2021</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-6 w-6 rounded-full bg-blue-500 flex items-center justify-center text-[10px] text-white font-bold mr-2">A</div>
                                <div class="text-sm text-gray-900">Andi Saputra</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Ganti Oli, Cek Rem
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-100">
                                <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-1.5"></span>
                                Proses
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Budi</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-gray-400 hover:text-orange-600"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
                        </td>
                    </tr>
                    
                    </tbody>
            </table>
        </div>
    </div>
</div>