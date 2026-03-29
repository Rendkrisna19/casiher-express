<div>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="p-4 sm:p-8 font-[Poppins]" x-data="{ 
        showBarcode: false, 
        barcodeSrc: '', 
        barangNama: '',
        konfirmasiHapus(id) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Data barang ini akan dihapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#cc0000',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.delete(id); 
                }
            });
        }
    }">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white uppercase tracking-tight">Manajemen Stok & Barcode</h1>
            <button wire:click="create" class="bg-[#cc0000] text-white px-6 py-2 rounded-xl font-bold shadow-lg hover:bg-red-700 transition active:scale-95">
                + Tambah Barang
            </button>
        </div>

        <div class="mb-6">
            <input type="text" wire:model.live="search" placeholder="Cari Nama atau Kode Barang..." class="w-full max-w-md px-4 py-2 rounded-xl border-gray-200 dark:bg-[#1e1e1e] dark:border-gray-700 dark:text-white focus:ring-[#cc0000]">
        </div>

        <div class="bg-white dark:bg-[#1e1e1e] rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-[#252525] text-xs uppercase text-gray-500 font-bold">
                    <tr>
                        <th class="px-6 py-4">Barcode</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Harga</th>
                        <th class="px-6 py-4 text-center">Stok</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($barangs as $b)
                    <tr class="dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition group">
                        <td class="px-6 py-4">
                            <div class="relative inline-block cursor-pointer group/barcode" 
                                 @click="showBarcode = true; barcodeSrc = document.getElementById('bc-{{ $b->id }}').src; barangNama = '{{ $b->nama_barang }}'">
                                <div class="p-3 bg-white inline-block rounded-xl shadow-sm border group-hover/barcode:border-[#cc0000] transition-colors overflow-hidden">
                                    <img id="bc-{{ $b->id }}" 
                                         x-init="setTimeout(() => { JsBarcode($el, '{{ $b->kode_barang }}', {format: 'CODE128', displayValue: true, width: 2.5, height: 60, margin: 15, fontSize: 16, background: '#ffffff', lineColor: '#000000'}) }, 50)" 
                                         class="h-12 w-auto object-contain bg-white block">
                                </div>
                                <div class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover/barcode:opacity-100 flex items-center justify-center transition-opacity">
                                    <svg class="w-6 h-6 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium">
                            {{ $b->nama_barang }}
                            <div class="text-[10px] text-gray-400 font-mono mt-1">SKU: {{ $b->kode_barang }}</div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-600 dark:text-gray-400">Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-black {{ $b->stok <= 5 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                {{ $b->stok }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button type="button" wire:click="edit({{ $b->id }})" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button type="button" @click="konfirmasiHapus({{ $b->id }})" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">{{ $barangs->links() }}</div>
        </div>

        <div x-show="showBarcode" style="display: none;" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-[#1e1e1e] rounded-3xl p-8 max-w-md w-full text-center shadow-2xl animate-fade-in" @click.away="showBarcode = false">
                <h3 class="text-lg font-black dark:text-white mb-1 uppercase tracking-tight" x-text="barangNama"></h3>
                <p class="text-xs text-gray-500 mb-6 font-bold uppercase tracking-widest">Barcode Preview</p>
                
                <div class="bg-white p-6 rounded-2xl shadow-inner border mx-auto flex items-center justify-center mb-8 w-full overflow-hidden">
                    <img :src="barcodeSrc" class="w-full max-h-32 object-contain block bg-white">
                </div>

                <div class="flex flex-col gap-3">
                    <a :href="barcodeSrc" :download="'Barcode_' + barangNama + '.png'" 
                       class="bg-[#cc0000] text-white py-3 rounded-xl font-black shadow-lg shadow-red-500/30 flex items-center justify-center gap-2 hover:bg-red-700 transition active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        DOWNLOAD BARCODE
                    </a>
                    <button @click="showBarcode = false" class="text-gray-400 font-bold text-sm hover:text-gray-600 transition">Tutup Preview</button>
                </div>
            </div>
        </div>

        @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-[#1e1e1e] p-8 rounded-2xl w-full max-w-md shadow-2xl border dark:border-gray-700 animate-fade-in">
                <h2 class="text-xl font-bold mb-6 dark:text-white uppercase">{{ $barangId ? 'Edit Data Barang' : 'Tambah Barang Baru' }}</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1">Kode Barang (Barcode)</label>
                        <input type="text" wire:model="kode_barang" placeholder="Auto-generate jika dikosongkan" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-[#cc0000] transition-all font-mono font-bold">
                        <p class="text-[10px] text-gray-400 mt-1">Sistem otomatis membuat kode acak, Anda bebas menggantinya.</p>
                        @error('kode_barang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 mb-1">Nama Barang</label>
                        <input type="text" wire:model="nama_barang" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-[#cc0000] transition-all">
                        @error('nama_barang') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-400 mb-1">Harga (Rp)</label>
                            <input type="number" wire:model="harga" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-[#cc0000] transition-all text-right font-bold">
                            @error('harga') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-400 mb-1">Stok</label>
                            <input type="number" wire:model="stok" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-[#cc0000] transition-all text-right font-bold">
                            @error('stok') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button wire:click="closeModal" class="flex-1 px-4 py-3 bg-gray-100 dark:bg-gray-800 dark:text-white rounded-xl font-bold transition hover:bg-gray-200">Batal</button>
                    <button wire:click="store" class="flex-1 px-4 py-3 bg-[#cc0000] text-white rounded-xl font-bold shadow-lg shadow-red-500/30 hover:bg-red-700 transition">SIMPAN DATA</button>
                </div>
            </div>
        </div>
        @endif
    </div>

    @script
    <script>
        window.addEventListener('swal', function(event) {
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    </script>
    @endscript
</div>