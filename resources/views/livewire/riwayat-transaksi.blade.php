<div class="p-4 sm:p-8 font-[Poppins] animate-fade-in">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 dark:text-white uppercase tracking-tighter">Riwayat Transaksi</h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Log Aktivitas Stok Barang</p>
        </div>
        <button wire:click="resetFilter" class="flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors text-xs font-bold text-gray-600 dark:text-gray-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            RESET FILTER
        </button>
    </div>

    <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-xl mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-1">
                <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-wider">Cari Data</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" wire:model.live.debounce.500ms="search" placeholder="Nama, SKU, Ket..." class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#cc0000] dark:text-white transition-all outline-none">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-wider">Mulai Tanggal</label>
                <input type="date" wire:model.live="tgl_awal" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#cc0000] dark:text-white transition-all outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" wire:model.live="tgl_akhir" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#cc0000] dark:text-white transition-all outline-none">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-wider">Jenis</label>
                <select wire:model.live="jenis_filter" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-[#cc0000] dark:text-white transition-all outline-none appearance-none">
                    <option value="">Semua Transaksi</option>
                    <option value="masuk">Barang Masuk (+)</option>
                    <option value="keluar">Barang Keluar (-)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1e1e1e] rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-wider whitespace-nowrap">Waktu</th>
                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-wider whitespace-nowrap">Barang</th>
                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-wider whitespace-nowrap">Jenis</th>
                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-wider whitespace-nowrap text-center">Jumlah</th>
                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-wider">Keterangan</th>
                        <th class="p-4 text-xs font-black text-gray-400 uppercase tracking-wider text-center">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="p-4 whitespace-nowrap">
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }} WIB</p>
                            </td>
                            <td class="p-4">
                                <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $log->barang->nama_barang ?? 'Barang Terhapus' }}</p>
                                <p class="text-[10px] font-mono text-gray-400">{{ $log->barang->kode_barang ?? '-' }}</p>
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                @if($log->jenis === 'masuk')
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full text-[10px] font-black uppercase flex items-center gap-1 w-max">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                                        Masuk
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full text-[10px] font-black uppercase flex items-center gap-1 w-max">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                                        Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <span class="text-lg font-black {{ $log->jenis === 'masuk' ? 'text-green-600' : 'text-[#cc0000]' }}">
                                    {{ $log->jenis === 'masuk' ? '+' : '-' }}{{ $log->jumlah }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                {{ $log->keterangan ?: '-' }}
                            </td>
                            <td class="p-4 text-center">
                                @if($log->bukti_transaksi)
                                    <a href="{{ Storage::url($log->bukti_transaksi) }}" target="_blank" class="inline-block p-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-xl hover:bg-blue-100 transition-colors" title="Lihat Bukti">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-sm font-medium">Tidak ada riwayat transaksi ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/30">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>