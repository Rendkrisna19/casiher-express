<div class="w-full relative" x-data="{ showModal: false }">
    <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-6">Hitung Penghasilan</h1>

    <div class="flex flex-col xl:flex-row gap-6 animate-fade-in-up">
        
        <div class="bg-white dark:bg-[#1e1e1e] p-6 sm:p-8 rounded-xl border border-gray-200 dark:border-gray-800 flex-1 shadow-sm">
            <div class="space-y-4">
                
                <div class="flex items-center gap-4">
                    <label class="w-1/3 text-sm font-bold text-gray-700 dark:text-gray-300">Sisa Kasir <span class="text-red-500">*</span></label>
                    <div class="relative w-2/3">
                        <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp.</span>
                        <input type="number" wire:model.lazy="sisa_kasir" wire:change="autoSave" class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-yellow-50 dark:bg-yellow-900/10 text-gray-900 dark:text-white text-right font-bold focus:ring-[#cc0000] focus:border-[#cc0000] transition-colors">
                    </div>
                </div>

                <div class="flex items-center gap-4 py-2">
                    <label class="w-1/3 text-sm font-bold text-gray-700 dark:text-gray-300 text-red-600">Hasil Penjualan (HP)</label>
                    <div class="relative w-2/3">
                        <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp.</span>
                        <input type="text" readonly value="{{ number_format($this->hasilPenjualan, 0, ',', '.') }}" class="w-full pl-10 pr-4 py-2 border border-red-200 dark:border-red-900/50 rounded-md bg-red-50 dark:bg-red-900/20 text-[#cc0000] dark:text-red-400 text-right font-black focus:outline-none cursor-not-allowed">
                        <p class="text-[10px] text-gray-400 mt-1">*Sisa Kasir + UA + Saldo Masuk Rek</p>
                    </div>
                </div>

                @php
                    $inputs = [
                        ['label' => 'Saldo Sebelum', 'model' => 'saldo_sebelum'],
                        ['label' => 'Saldo Masuk Rek', 'model' => 'saldo_masuk_rek'],
                        ['label' => 'Setor Tunai', 'model' => 'setor_tunai'],
                        ['label' => 'Kas Masuk', 'model' => 'kas_masuk'],
                        ['label' => 'Saldo Rek', 'model' => 'saldo_rek'],
                    ];
                @endphp

                @foreach($inputs as $input)
                <div class="flex items-center gap-4">
                    <label class="w-1/3 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $input['label'] }}</label>
                    <div class="relative w-2/3">
                        <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp.</span>
                        <input type="number" wire:model.lazy="{{ $input['model'] }}" wire:change="autoSave" class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-right focus:ring-[#cc0000] focus:border-[#cc0000] transition-colors">
                    </div>
                </div>
                @endforeach

                <div class="flex items-start gap-4 py-4 border-t border-gray-200 dark:border-gray-800 mt-4">
                    <div class="w-1/3">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Total Pengeluaran</label>
                        <p class="text-[9px] font-bold text-red-500 mt-1">*Hanya UA memotong Saldo Bawah</p>
                    </div>
                    <div class="w-2/3 flex gap-2">
                        <div class="relative flex-1">
                            <span class="absolute left-3 top-2.5 text-gray-500 text-sm">Rp.</span>
                            <input type="text" readonly value="{{ number_format($this->totalPengeluaran, 0, ',', '.') }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white text-right font-bold focus:outline-none cursor-not-allowed">
                        </div>
                        <button @click="showModal = true" type="button" class="bg-[#cc0000] hover:bg-red-700 text-white text-sm font-bold px-4 py-2 rounded-md transition whitespace-nowrap flex items-center gap-2 h-[42px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Input
                        </button>
                    </div>
                </div>

            </div>

            <div class="flex justify-between mt-8">
                <a href="{{ route('dashboard') }}" wire:navigate class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 px-6 py-2.5 rounded-md font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center gap-2">
                    Dashboard
                </a>
                <button wire:click="autoSave" class="bg-[#cc0000] text-white px-8 py-2.5 rounded-md font-bold hover:bg-red-700 transition flex items-center gap-2 shadow-md">
                    Hitung & Simpan
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e1e1e] p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 w-full xl:w-[450px] text-gray-900 dark:text-gray-100 transition-colors flex flex-col">
            
            <div class="flex justify-center border-b-2 border-gray-300 dark:border-gray-700 pb-4 mb-4">
                <img src="{{ asset('assets/images/logo.jpg') }}" alt="EXPRESS" class="h-10 object-contain dark:invert transition-all">
            </div>
            
            <div class="flex justify-between text-xs mb-6 font-medium border-b border-gray-200 dark:border-gray-800 pb-4">
                <div>
                    <p>Admin : {{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p>Waktu : {{ $waktu_transaksi }}</p>
                </div>
                <div class="text-right">
                    <p>Tanggal Transaksi :</p>
                    <p>{{ $tanggal_transaksi }}</p>
                </div>
            </div>

            <div class="space-y-2 text-sm flex-1 overflow-y-auto custom-scrollbar pr-2">
                <div class="flex justify-between border-b border-gray-300 dark:border-gray-700 pb-1 mb-2 font-bold">
                    <span>Nama Transaksi</span>
                    <span>Rp.</span>
                </div>

                <div class="flex justify-between"><span>Sisa Kasir</span><span>{{ number_format($sisa_kasir, 0, ',', '.') }}</span></div>
                <div class="flex justify-between font-bold text-[#cc0000] italic"><span>Hasil Penjualan (HP)</span><span>{{ number_format($this->hasilPenjualan, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Saldo Sebelum</span><span>{{ number_format($saldo_sebelum, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Saldo Masuk Rek</span><span>{{ number_format($saldo_masuk_rek, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Setor Tunai</span><span>{{ number_format($setor_tunai, 0, ',', '.') }}</span></div>
                
                <div class="flex justify-between text-gray-600 dark:text-gray-400 font-bold bg-gray-50 dark:bg-gray-900/10 px-1 rounded mt-1">
                    <span>Kas Masuk (+)</span>
                    <span>{{ number_format($kas_masuk, 0, ',', '.') }}</span>
                </div>
                
                @if(count($pengeluaranItems) > 0)
                    <div class="pt-2 mt-2 border-t border-dashed border-gray-300 dark:border-gray-700">
                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider">Rincian Pengeluaran:</span>
                        @foreach($pengeluaranItems as $index => $item)
                            <div class="flex justify-between text-[#cc0000] dark:text-red-400 text-xs mt-1 items-center group transition-all">
                                <span class="flex items-center gap-1">
                                    <button wire:click="removePengeluaran({{ $index }})" class="text-red-500 hover:text-red-800 opacity-0 group-hover:opacity-100 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    <span class="font-bold">[{{ $item['jenis'] ?? 'UA' }}]</span> {{ $item['nama'] }}
                                </span>
                                <span>{{ number_format($item['price'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <div class="flex justify-between pt-4 border-t border-gray-300 dark:border-gray-700 mt-4 font-bold">
                    <span>Saldo Rek</span><span>{{ number_format($saldo_rek, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t-2 border-gray-900 dark:border-gray-100 flex justify-between items-center bg-gray-50 dark:bg-white/5 p-4 rounded-lg">
                <span class="text-lg font-black uppercase">SALDO BAWAH</span>
                <span class="text-2xl font-black text-[#cc0000]">Rp. {{ number_format($this->saldoBawah, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" @click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="inline-block align-bottom bg-white dark:bg-[#1e1e1e] rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200 dark:border-gray-700">
                
                <div class="bg-gray-50 dark:bg-[#181818] px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-black text-gray-900 dark:text-white" id="modal-title">
                        Tambah Pengeluaran Baru
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-red-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Jenis Pengeluaran</label>
                        <select wire:model="new_jenis" class="w-full p-2.5 border border-gray-300 dark:border-gray-700 rounded-md text-sm bg-white dark:bg-[#121212] focus:ring-[#cc0000] focus:border-[#cc0000] text-gray-900 dark:text-white font-bold">
                            <option value="UA">UA - Uang Atas (Memotong Saldo)</option>
                            <option value="UB">UB - Uang Bawah</option>
                            <option value="UR">UR - Uang Rekening</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nama Transaksi</label>
                        <input type="text" wire:model="new_nama_transaksi" placeholder="Cth: Beli Kertas HVS..." class="w-full p-2.5 border border-gray-300 dark:border-gray-700 rounded-md text-sm bg-white dark:bg-[#121212] focus:ring-[#cc0000] focus:border-[#cc0000] text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nominal (Rp)</label>
                        <input type="number" wire:model="new_price" placeholder="50000" class="w-full p-2.5 border border-gray-300 dark:border-gray-700 rounded-md text-sm bg-white dark:bg-[#121212] focus:ring-[#cc0000] focus:border-[#cc0000] text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Keterangan (Opsional)</label>
                        <input type="text" wire:model="new_keterangan" placeholder="Nota dari toko A..." class="w-full p-2.5 border border-gray-300 dark:border-gray-700 rounded-md text-sm bg-white dark:bg-[#121212] focus:ring-[#cc0000] focus:border-[#cc0000] text-gray-900 dark:text-white">
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-[#181818] px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 font-bold py-2 px-4 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="button" x-on:click="$wire.addPengeluaran().then(() => { showModal = false })" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-2 px-6 rounded-md shadow-md transition">
                        Tambahkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('swal', (event) => {
                const data = event[0] || event.detail; 
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    toast: true,
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