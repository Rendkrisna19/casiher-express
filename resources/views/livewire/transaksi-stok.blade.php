<div class="p-4 sm:p-8 font-[Poppins] animate-fade-in" x-data="{ scanning: false }">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 dark:text-white uppercase tracking-tighter">Inventory Scanner</h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Transaksi Keluar & Masuk Barang v2.0</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
            <span class="text-[10px] font-bold text-gray-500">Sistem Siap</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-5 flex flex-col gap-6 relative">
            
            <div class="relative w-full z-50">
                <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">1. Pilih Manual</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="searchManual" placeholder="Ketik Nama Barang / SKU..." class="w-full pl-11 pr-4 py-3.5 bg-white dark:bg-[#1e1e1e] border border-gray-200 dark:border-gray-800 rounded-2xl text-sm focus:ring-[#cc0000] focus:border-[#cc0000] shadow-sm font-medium transition-all">
                </div>
                
                @if(!empty($searchResults))
                <ul class="absolute z-50 w-full mt-2 bg-white dark:bg-[#1e1e1e] border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl overflow-hidden max-h-60 overflow-y-auto">
                    @foreach($searchResults as $res)
                    <li wire:click="pilihBarangManual({{ $res->id }})" class="px-5 py-3 hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer border-b border-gray-50 dark:border-gray-800 transition-colors flex justify-between items-center group">
                        <div>
                            <div class="font-bold text-sm text-gray-900 dark:text-white group-hover:text-[#cc0000]">{{ $res->nama_barang }}</div>
                            <div class="text-[10px] text-gray-400 font-mono mt-0.5">SKU: {{ $res->kode_barang }}</div>
                        </div>
                        <span class="text-xs font-black {{ $res->stok <= 5 ? 'text-red-500' : 'text-green-500' }}">{{ $res->stok }} Stok</span>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

            <div class="relative w-full z-10">
                <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">2. Scan / Upload Barcode</label>
                
                <div class="bg-black rounded-[2rem] overflow-hidden relative aspect-square shadow-2xl border-4 border-gray-800 dark:border-[#2a2a2a]">
                    
                    <div wire:ignore class="w-full h-full absolute inset-0">
                        <div id="reader" class="w-full h-full bg-black"></div>
                    </div>
                    
                    <div x-show="!scanning" class="absolute inset-0 flex items-center justify-center bg-gray-900/95 text-white flex-col gap-4 p-8 text-center z-10">
                        <div class="w-14 h-14 rounded-full bg-red-600/20 flex items-center justify-center mb-2">
                            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                        </div>

                        <button type="button" onclick="mulaiKamera()" @click="scanning = true" class="w-full bg-[#cc0000] hover:bg-red-700 px-6 py-3 rounded-2xl font-black shadow-lg shadow-red-500/40 transition-all active:scale-95 text-xs">
                            AKTIFKAN KAMERA
                        </button>

                        <div class="flex items-center gap-3 w-full opacity-50 my-1">
                            <div class="h-px bg-gray-400 flex-1"></div>
                            <span class="text-[9px] font-bold tracking-widest uppercase">Atau</span>
                            <div class="h-px bg-gray-400 flex-1"></div>
                        </div>

                        <label class="w-full cursor-pointer bg-gray-800 hover:bg-gray-700 border border-gray-600 px-6 py-3 rounded-2xl font-black transition-all active:scale-95 text-xs flex justify-center items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            UPLOAD DARI GALERI
                            <div wire:ignore>
                                <input type="file" id="qr-upload-input" class="hidden" accept="image/*" onchange="handleImageUpload(event)">
                            </div>
                        </label>
                    </div>

                    <div x-show="scanning" class="absolute inset-0 pointer-events-none border-[30px] border-black/50 z-10 flex flex-col justify-between">
                        <button type="button" onclick="hentikanKamera()" @click="scanning = false" class="pointer-events-auto absolute top-[-20px] right-[-20px] bg-red-600 text-white rounded-full p-2 hover:bg-red-700 shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="w-full h-1 bg-red-500 absolute top-1/2 left-0 animate-pulse shadow-[0_0_20px_rgba(239,68,68,1)]"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-7 relative z-0">
            @if($barangTerpilih)
                <div class="bg-white dark:bg-[#1e1e1e] p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-xl space-y-6 animate-fade-in-up">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="px-3 py-1 bg-blue-500/10 text-blue-600 rounded-full text-[10px] font-black uppercase mb-2 inline-block">Produk Terpilih</span>
                            <h2 class="text-3xl font-black dark:text-white tracking-tight">{{ $barangTerpilih->nama_barang }}</h2>
                            <p class="font-mono text-sm text-[#cc0000] font-bold mt-1">SKU: {{ $barangTerpilih->kode_barang }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Sisa Stok</span>
                            <p class="text-3xl font-black text-gray-800 dark:text-gray-200">{{ $barangTerpilih->stok }}</p>
                        </div>
                    </div>

                    <hr class="border-dashed border-gray-200 dark:border-gray-800">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 mb-2 uppercase">Aksi Stok</label>
                            <div class="flex p-1 bg-gray-100 dark:bg-[#111] rounded-xl border border-gray-200 dark:border-gray-700">
                                <button wire:click="$set('jenis', 'masuk')" class="flex-1 py-2.5 rounded-lg text-xs font-black transition-all {{ $jenis === 'masuk' ? 'bg-white dark:bg-[#2a2a2a] shadow-sm text-green-600' : 'text-gray-400' }}">MASUK (+)</button>
                                <button wire:click="$set('jenis', 'keluar')" class="flex-1 py-2.5 rounded-lg text-xs font-black transition-all {{ $jenis === 'keluar' ? 'bg-white dark:bg-[#2a2a2a] shadow-sm text-red-600' : 'text-gray-400' }}">KELUAR (-)</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 mb-2 uppercase">Jumlah Unit</label>
                            <input type="number" wire:model="jumlah" min="1" class="w-full px-4 py-2 bg-white dark:bg-[#111] border border-gray-200 dark:border-gray-700 rounded-xl font-black text-lg focus:ring-2 focus:ring-red-600 text-right transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 mb-2 uppercase">Keterangan Transaksi</label>
                        <input type="text" wire:model="keterangan" placeholder="Contoh: Restock Toko A atau Barang Retur..." class="w-full px-4 py-3 bg-white dark:bg-[#111] border border-gray-200 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-red-600 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 mb-2 uppercase">Unggah Bukti Nota (Opsional)</label>
                        <div class="flex items-center gap-4">
                            <div class="relative group h-24 w-24 bg-gray-50 dark:bg-[#111] rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center overflow-hidden">
                                @if ($bukti)
                                    <img src="{{ $bukti->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4-4m4 4V4"></path></svg>
                                @endif
                                <input type="file" wire:model="bukti" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] text-gray-400 leading-relaxed font-medium">Khusus untuk lampiran bukti transaksi. Foto nota atau resi fisik.</p>
                                <div wire:loading wire:target="bukti" class="text-[10px] text-[#cc0000] font-bold mt-1 animate-pulse">Mengunggah file...</div>
                            </div>
                        </div>
                    </div>

                    <button wire:click="simpanTransaksi" 
                            wire:loading.attr="disabled"
                            class="w-full bg-[#cc0000] hover:bg-red-700 text-white py-4 rounded-2xl font-black shadow-xl shadow-red-500/30 transition-all active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-2 mt-4">
                        <span wire:loading.remove wire:target="simpanTransaksi">SIMPAN DATA TRANSAKSI</span>
                        <span wire:loading wire:target="simpanTransaksi" class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                    </button>
                </div>
            @else
                <div class="h-full min-h-[450px] border-4 border-dashed border-gray-200 dark:border-gray-800 rounded-[2rem] flex flex-col items-center justify-center text-center p-8 bg-gray-50/50 dark:bg-transparent">
                    <div class="w-24 h-24 bg-white dark:bg-gray-800 rounded-full shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-400 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-400 uppercase tracking-tight">Menunggu Item...</h3>
                    <p class="text-xs text-gray-400 max-w-xs mt-2 font-medium">Gunakan kotak pencarian atau nyalakan kamera untuk mendeteksi barang yang akan di-update stoknya.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    
    <script>
        let html5QrCode = null;

        // FUNGSI 1: START KAMERA YANG BENAR
        function mulaiKamera() {
            // Jika ada kamera yang masih nyangkut, matikan dulu
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    jalankanScanner();
                }).catch(() => { jalankanScanner(); });
            } else {
                jalankanScanner();
            }
        }

        // FUNGSI 2: EKSEKUSI KAMERA
        function jalankanScanner() {
            html5QrCode = new Html5Qrcode("reader");
            
            const config = { 
                fps: 10,
                qrbox: { width: 300, height: 150 }, 
                aspectRatio: 1.0,
            };

            html5QrCode.start({ facingMode: "environment" }, config, 
                (decodedText) => {
                    // Berhasil scan!
                    if (navigator.vibrate) navigator.vibrate(100);
                    
                    @this.set('searchCode', decodedText); // Kirim ke Backend
                    
                    // Matikan kamera otomatis agar tidak ngebug
                    hentikanKamera();
                    document.querySelector('[x-data]').__x.$data.scanning = false;
                },
                (errorMessage) => { /* Abaikan error mencari fokus */ }
            ).catch(err => {
                Swal.fire({ title: 'Gagal', text: 'Izin kamera ditolak browser.', icon: 'error' });
                document.querySelector('[x-data]').__x.$data.scanning = false;
            });
        }

        // FUNGSI 3: MATIKAN KAMERA DENGAN BERSIH
        function hentikanKamera() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    html5QrCode = null; // Hapus instansi agar bisa dibuat ulang nanti
                }).catch(err => console.error(err));
            }
        }

        // FUNGSI 4: UPLOAD GAMBAR
        function handleImageUpload(event) {
            if (event.target.files.length === 0) return;
            const file = event.target.files[0];
            
            if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");

            html5QrCode.scanFile(file, true)
                .then(decodedText => {
                    if (navigator.vibrate) navigator.vibrate([100]);
                    @this.set('searchCode', decodedText);
                })
                .catch(err => {
                    Swal.fire({ title: 'Tidak Terbaca!', text: 'Pastikan Barcode jelas.', icon: 'error' });
                });
            
            event.target.value = ''; 
        }

        // FUNGSI 5: LISTENER SWEETALERT & AUTO-RELOAD
        document.addEventListener('livewire:initialized', () => {
            
            // Swal biasa (tanpa reload)
            @this.on('swal', (event) => {
                const data = event[0] || event.detail;
                Swal.fire({
                    title: data.title, text: data.text, icon: data.icon,
                    timer: data.timer || 2000, showConfirmButton: false, toast: true, position: 'top-end'
                });
            });

            // Swal sukses lalu AUTO-RELOAD halaman
            @this.on('swal-reload', (event) => {
                const data = event[0] || event.detail;
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    timer: 2000, // Tampil 2 detik
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload(); // HARD RELOAD DI SINI
                });
            });

        });
    </script>
</div>