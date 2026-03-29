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
        
        <div class="lg:col-span-5 flex flex-col gap-6">
            <div class="bg-black rounded-[2rem] overflow-hidden relative aspect-square shadow-2xl border-8 border-gray-800 dark:border-[#1e1e1e]">
                <div id="reader" class="w-full h-full bg-black"></div>
                
                <div x-show="!scanning" class="absolute inset-0 flex items-center justify-center bg-gray-900/95 text-white flex-col gap-5 p-8 text-center z-10">
                    <div class="w-16 h-16 rounded-full bg-red-600/20 flex items-center justify-center mb-2">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4V4zm2 4h2v8H6V8zm4 0h1v8h-1V8zm3 0h2v8h-2V8zm4 0h1v8h-1V8z"></path></svg>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-lg">Pilih Mode Deteksi</h4>
                        <p class="text-xs text-gray-400 mt-1 mb-2">Gunakan kamera atau upload foto Barcode dari galeri.</p>
                    </div>

                    <button @click="startScanner(); scanning = true" class="w-full bg-[#cc0000] hover:bg-red-700 px-6 py-3 rounded-2xl font-black shadow-xl shadow-red-500/40 transition-all active:scale-95 text-sm">
                        📸 AKTIFKAN KAMERA
                    </button>

                    <div class="flex items-center gap-3 w-full opacity-50">
                        <div class="h-px bg-gray-400 flex-1"></div>
                        <span class="text-[10px] font-bold tracking-widest uppercase">Atau</span>
                        <div class="h-px bg-gray-400 flex-1"></div>
                    </div>

                    <label class="w-full cursor-pointer bg-gray-800 hover:bg-gray-700 border border-gray-600 px-6 py-3 rounded-2xl font-black transition-all active:scale-95 text-sm flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        UPLOAD FOTO BARCODE
                        <input type="file" id="qr-upload-input" class="hidden" accept="image/*" onchange="handleImageUpload(event)">
                    </label>
                </div>

                <div x-show="scanning" class="absolute inset-0 pointer-events-none border-[40px] border-black/30 z-10 flex flex-col justify-between">
                    <button @click="stopScanner(); scanning = false" class="pointer-events-auto absolute top-[-30px] right-[-30px] bg-red-600 text-white rounded-full p-2 hover:bg-red-700 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="w-full h-1 bg-red-600 absolute top-1/2 left-0 animate-bounce shadow-[0_0_15px_rgba(220,38,38,0.8)]"></div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-7">
            @if($barangTerpilih)
                <div class="bg-white dark:bg-[#1e1e1e] p-8 rounded-[2rem] border border-gray-100 dark:border-gray-800 shadow-xl space-y-6 animate-fade-in-up">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="px-3 py-1 bg-blue-500/10 text-blue-600 rounded-full text-[10px] font-black uppercase mb-2 inline-block">Produk Terpilih</span>
                            <h2 class="text-3xl font-black dark:text-white tracking-tight">{{ $barangTerpilih->nama_barang }}</h2>
                            <p class="font-mono text-sm text-gray-400 font-bold mt-1">SKU: {{ $barangTerpilih->kode_barang }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Stok Sistem</span>
                            <p class="text-3xl font-black text-gray-800 dark:text-gray-200">{{ $barangTerpilih->stok }}</p>
                        </div>
                    </div>

                    <hr class="dark:border-gray-800">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 mb-2 uppercase">Aksi Stok</label>
                            <div class="flex p-1 bg-gray-100 dark:bg-gray-800 rounded-xl">
                                <button wire:click="$set('jenis', 'masuk')" class="flex-1 py-2 rounded-lg text-xs font-black transition-all {{ $jenis === 'masuk' ? 'bg-white dark:bg-gray-700 shadow-sm text-green-600' : 'text-gray-400' }}">MASUK (+)</button>
                                <button wire:click="$set('jenis', 'keluar')" class="flex-1 py-2 rounded-lg text-xs font-black transition-all {{ $jenis === 'keluar' ? 'bg-white dark:bg-gray-700 shadow-sm text-red-600' : 'text-gray-400' }}">KELUAR (-)</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 mb-2 uppercase">Jumlah Unit</label>
                            <input type="number" wire:model="jumlah" min="1" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border-none rounded-xl font-black text-lg focus:ring-2 focus:ring-red-600 text-right">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 mb-2 uppercase">Keterangan Transaksi</label>
                        <input type="text" wire:model="keterangan" placeholder="Contoh: Restock Supplier A atau Penjualan Retail..." class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-red-600">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 mb-2 uppercase">Unggah Bukti Nota/Barang (Opsional)</label>
                        <div class="flex items-center gap-4">
                            <div class="relative group h-24 w-24 bg-gray-100 dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 flex items-center justify-center overflow-hidden">
                                @if ($bukti)
                                    <img src="{{ $bukti->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                                <input type="file" wire:model="bukti" accept="image/png, image/jpeg, image/jpg" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] text-gray-400 leading-tight">Klik kotak di samping untuk memilih foto nota. Khusus untuk simpan bukti transaksi.</p>
                                <div wire:loading wire:target="bukti" class="text-[10px] text-blue-500 font-bold mt-1">Mengunggah file...</div>
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
                <div class="h-full min-h-[400px] border-4 border-dashed border-gray-100 dark:border-gray-800 rounded-[2rem] flex flex-col items-center justify-center text-center p-8">
                    <div class="w-24 h-24 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12 text-gray-300 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4h16v16H4V4zm2 4h2v8H6V8zm4 0h1v8h-1V8zm3 0h2v8h-2V8zm4 0h1v8h-1V8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-400">Menunggu Deteksi...</h3>
                    <p class="text-xs text-gray-400 max-w-xs mt-2 italic">Silakan nyalakan kamera atau upload foto Barcode di panel sebelah kiri untuk memunculkan detail barang.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    
    <script>
        let html5QrCode;
        let isProcessing = false;

        // FUNGSI INISIALISASI SCANNER DENGAN DUKUNGAN 1D BARCODE
        function initScanner() {
            // Memaksa scanner untuk membaca 1D Barcode (CODE_128, EAN, UPC) dan QR Code
            return new Html5Qrcode("reader", {
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.CODE_39,
                    Html5QrcodeSupportedFormats.EAN_13,
                    Html5QrcodeSupportedFormats.UPC_A,
                    Html5QrcodeSupportedFormats.QR_CODE
                ]
            });
        }

        document.addEventListener('livewire:initialized', () => {
            html5QrCode = initScanner();

            @this.on('swal', (event) => {
                const data = event[0];
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    timer: data.timer || null,
                    showConfirmButton: data.timer ? false : true,
                    confirmButtonColor: '#cc0000',
                    confirmButtonText: 'OK, Siap!'
                });
            });
        });

        function startScanner() {
            const config = { 
                fps: 10,
                // UBAH: Kotak deteksi (qrbox) dibikin PERSEGI PANJANG (lebar 300, tinggi 150)
                qrbox: { width: 300, height: 150 },
                aspectRatio: 1.0,
                experimentalFeatures: { useBarCodeDetectorIfSupported: true }
            };

            html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                (decodedText) => {
                    if (!isProcessing && @this.get('searchCode') !== decodedText) {
                        isProcessing = true;
                        if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
                        
                        @this.set('searchCode', decodedText);
                        
                        setTimeout(() => { isProcessing = false; }, 1500);
                    }
                }
            ).catch(err => {
                Swal.fire({
                    title: 'Kamera Gagal!',
                    text: 'Pastikan browser mengizinkan kamera. Gunakan tombol UPLOAD FOTO BARCODE sebagai ganti.',
                    icon: 'warning',
                    confirmButtonColor: '#cc0000'
                });
                document.querySelector('[x-data]').__x.$data.scanning = false;
            });
        }

        function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().catch(err => console.error(err));
            }
        }

        function handleImageUpload(event) {
            if (event.target.files.length === 0) return;
            
            const file = event.target.files[0];
            
            // Jika belum ada instansi scanner, buat baru dengan dukungan Barcode 1D
            if (!html5QrCode) html5QrCode = initScanner();

            html5QrCode.scanFile(file, true)
                .then(decodedText => {
                    if (navigator.vibrate) navigator.vibrate([100]);
                    @this.set('searchCode', decodedText);
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Gagal Membaca Barcode!',
                        text: 'Pastikan gambar Barcode Garis cukup besar, jelas, dan tidak terpotong garisnya.',
                        icon: 'error',
                        confirmButtonColor: '#cc0000'
                    });
                });
            
            event.target.value = ''; // Reset input agar bisa upload file yang sama lagi
        }
    </script>
</div>