<div class="w-full relative">
    <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-6">Hitung Penghasilan</h1>

    @if($viewMode === 'main')
        <div class="flex flex-col xl:flex-row gap-6 animate-fade-in-up">
            
            <div class="bg-white dark:bg-[#1e1e1e] p-6 sm:p-8 rounded-xl border border-gray-200 dark:border-gray-800 flex-1 shadow-sm">
                <div class="space-y-4">
                    @php
                        $inputs = [
                            ['label' => 'Hasil Penjualan', 'model' => 'hasil_penjualan'],
                            ['label' => 'Saldo Sebelum', 'model' => 'saldo_sebelum'],
                            ['label' => 'Saldo Masuk Rek', 'model' => 'saldo_masuk_rek'],
                            ['label' => 'Setor Tunai', 'model' => 'setor_tunai'],
                            ['label' => 'Kas Masuk', 'model' => 'kas_masuk'],
                        ];
                    @endphp

                    @foreach($inputs as $input)
                    <div class="flex items-center gap-4">
                        <label class="w-1/3 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $input['label'] }}</label>
                        <div class="relative w-2/3">
                            <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp.</span>
                            <input type="number" wire:model.lazy="{{ $input['model'] }}" wire:change="autoSave" class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-right focus:ring-primary focus:border-primary transition-colors">
                        </div>
                    </div>
                    @endforeach

                    <div class="flex items-center gap-4 py-2">
                        <label class="w-1/3 text-sm font-medium text-gray-700 dark:text-gray-300">Pengeluaran</label>
                        <div class="w-2/3 flex gap-2">
                            <div class="relative flex-1">
                                <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp.</span>
                                <input type="text" readonly value="{{ number_format($this->totalPengeluaran, 0, ',', '.') }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white text-right font-bold focus:outline-none cursor-not-allowed">
                            </div>
                            <button wire:click="setMode('pengeluaran')" class="bg-[#cc0000] hover:bg-red-700 text-white text-sm font-bold px-4 py-2 rounded-md transition whitespace-nowrap flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Input Pengeluaran
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <label class="w-1/3 text-sm font-medium text-gray-700 dark:text-gray-300">Saldo Rek</label>
                        <div class="relative w-2/3">
                            <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp.</span>
                            <input type="number" wire:model.lazy="saldo_rek" wire:change="autoSave" class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-right focus:ring-primary focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <a href="{{ route('dashboard') }}" wire:navigate class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-6 py-2.5 rounded-md font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Dashboard
                    </a>
                    <button wire:click="autoSave" class="bg-[#cc0000] text-white px-8 py-2.5 rounded-md font-bold hover:bg-red-700 transition flex items-center gap-2 shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Hitung & Simpan
                    </button>
                </div>
            </div>

           <div class="bg-white dark:bg-[#1e1e1e] p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 w-full xl:w-[450px] text-gray-900 dark:text-gray-100 transition-colors">
                
                <div class="flex justify-center border-b-2 border-gray-300 dark:border-gray-700 pb-4 mb-4">
                    <img src="{{ asset('assets/images/logo.jpg') }}" alt="EXPRESS" class="h-10 object-contain dark:invert transition-all">
                </div>
                
                <div class="flex justify-between text-xs mb-6 font-medium">
                    <div>
                        <p>Admin : {{ auth()->user()->name ?? 'Yola' }}</p>
                        <p>Waktu : {{ $waktu_transaksi }}</p>
                    </div>
                    <div class="text-right">
                        <p>Tanggal Transaksi :</p>
                        <p>{{ $tanggal_transaksi }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between border-b border-gray-300 dark:border-gray-700 pb-1 mb-2 font-bold">
                        <span>Nama Transaksi</span>
                        <span>Rp.</span>
                    </div>

                    <div class="flex justify-between"><span>Hasil Penjualan</span><span>{{ number_format($hasil_penjualan, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Saldo Sebelum</span><span>{{ number_format($saldo_sebelum, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Saldo Masuk Rek</span><span>{{ number_format($saldo_masuk_rek, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Setor Tunai</span><span>{{ number_format($setor_tunai, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span>Kas Masuk</span><span>{{ number_format($kas_masuk, 0, ',', '.') }}</span></div>
                    
                    @foreach($pengeluaranItems as $item)
                        <div class="flex justify-between text-[#cc0000] dark:text-red-400">
                            <span>{{ $item['nama'] }}</span>
                            <span>{{ number_format($item['price'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach

                    <div class="flex justify-between"><span>Saldo Rek</span><span>{{ number_format($saldo_rek, 0, ',', '.') }}</span></div>
                </div>

                <div class="mt-8 pt-4 border-t-2 border-gray-900 dark:border-gray-100 flex justify-between items-center">
                    <span class="text-lg font-bold">Saldo Bawah</span>
                    <span class="text-xl font-bold">Rp. {{ number_format($this->saldoBawah, 0, ',', '.') }}</span>
                </div>

                <div class="mt-8">
                    <button wire:click="autoSave" class="w-full bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 rounded-md flex items-center justify-center gap-2 transition shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Bukti
                    </button>
                </div>
            </div>
    @endif

    @if($viewMode === 'pengeluaran')
        <div class="bg-white dark:bg-[#1e1e1e] rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden animate-fade-in-up">
            <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-[#181818]">
                <h2 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#cc0000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Input Pengeluaran
                </h2>
                
                <button wire:click="setMode('main')" class="flex items-center gap-2 text-gray-500 hover:text-[#cc0000] font-bold transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Form
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#cc0000] text-white text-xs uppercase tracking-wider">
                            <th class="p-4 font-bold w-16">#</th>
                            <th class="p-4 font-bold">Nama Transaksi</th>
                            <th class="p-4 font-bold">Price (Rp)</th>
                            <th class="p-4 font-bold">Keterangan</th>
                            <th class="p-4 font-bold text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 text-sm text-gray-800 dark:text-gray-200">
                        @foreach($pengeluaranItems as $index => $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="p-4">{{ $index + 1 }}</td>
                            <td class="p-4 font-medium">{{ $item['nama'] }}</td>
                            <td class="p-4">Rp. {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="p-4 text-gray-500">{{ $item['keterangan'] }}</td>
                            <td class="p-4 text-center">
                                <button wire:click="removePengeluaran({{ $index }})" class="text-red-500 hover:text-red-700 bg-red-100 dark:bg-red-900/30 p-2 rounded-full transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        
                        <tr class="bg-gray-50 dark:bg-gray-800/30">
                            <td class="p-4 font-bold text-gray-400">+</td>
                            <td class="p-4"><input type="text" wire:model="new_nama_transaksi" placeholder="Cth: Beli Kertas..." class="w-full p-2.5 border border-gray-300 dark:border-gray-700 rounded-md text-sm dark:bg-[#121212] focus:ring-[#cc0000] focus:border-[#cc0000]"></td>
                            <td class="p-4"><input type="number" wire:model="new_price" placeholder="Nominal..." class="w-full p-2.5 border border-gray-300 dark:border-gray-700 rounded-md text-sm dark:bg-[#121212] focus:ring-[#cc0000] focus:border-[#cc0000]"></td>
                            <td class="p-4"><input type="text" wire:model="new_keterangan" placeholder="Keterangan..." class="w-full p-2.5 border border-gray-300 dark:border-gray-700 rounded-md text-sm dark:bg-[#121212] focus:ring-[#cc0000] focus:border-[#cc0000]"></td>
                            <td class="p-4 text-center">
                                <button wire:click="addPengeluaran" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-bold hover:bg-green-700 transition flex items-center gap-1 mx-auto shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-gray-50 dark:bg-[#181818] flex justify-between items-center border-t border-gray-200 dark:border-gray-800">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Total Pengeluaran Keseluruhan : Rp. {{ number_format($this->totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
                <button wire:click="setMode('main')" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-2.5 px-8 rounded-md flex items-center gap-2 transition shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Data
                </button>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Menangkap event 'swal' dari Livewire Component
            @this.on('swal', (event) => {
                // event[0] adalah data array yang kita kirim dari backend (title, text, icon)
                const data = event[0]; 
                
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    toast: true, // Ubah ke false jika ingin alert besar di tengah layar
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    background: localStorage.getItem('theme') === 'dark' ? '#1e1e1e' : '#fff',
                    color: localStorage.getItem('theme') === 'dark' ? '#fff' : '#000',
                });
            });
        });
    </script>
</div>