<div class="w-full relative">
    @if($currentView === 'menu')
        <div class="animate-fade-in-up">
            <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-8">Print Color Analysis</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl">
                @foreach($paperTypes as $paper => $details)
                    <button wire:click="selectPaper('{{ $paper }}')" class="bg-white dark:bg-[#1e1e1e] border-2 border-gray-200 dark:border-gray-700 hover:border-[#cc0000] text-gray-700 dark:text-gray-200 rounded-2xl p-8 h-48 flex items-center justify-center text-center font-medium text-lg transition-all hover:shadow-lg group">
                        <span class="group-hover:text-[#cc0000] transition-colors">{!! str_replace('Warna', 'Warna<br>', $paper) !!}</span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    @if($currentView === 'upload')
        <div class="animate-fade-in-up h-[80vh] flex flex-col items-center justify-center text-center">
            <h1 class="text-4xl font-black text-black dark:text-white mb-2">{{ $selectedPaper }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-10 max-w-md">Unggah dokumen untuk menghitung rincian biaya berdasarkan penggunaan tinta.</p>

            <div wire:loading wire:target="documentFile" class="mb-4">
                <div class="flex items-center gap-2 text-[#cc0000] font-bold">
                    <svg class="animate-spin h-6 w-6" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Sedang Menganalisa Dokumen...
                </div>
            </div>

            @error('documentFile') 
                <span class="text-red-500 mb-4 bg-red-100 p-2 rounded text-sm">{{ $message }}</span> 
            @enderror

            <div class="relative w-64 h-16 cursor-pointer group" wire:loading.remove wire:target="documentFile">
                <input type="file" wire:model="documentFile" accept=".pdf, .jpg, .jpeg, .png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="absolute inset-0 bg-[#cc0000] group-hover:bg-red-800 text-white font-bold rounded-xl flex items-center justify-center transition-all shadow-lg">
                    Pilih File
                </div>
            </div>
            
            <button wire:click="resetView" wire:loading.remove wire:target="documentFile" class="mt-12 text-gray-400 hover:text-[#cc0000] underline text-sm">Batal & Kembali</button>
        </div>
    @endif

    @if($currentView === 'result')
        <div class="animate-fade-in-up">
            <div class="flex flex-col xl:flex-row gap-6 h-[85vh]">
                
                <div class="bg-gray-200 dark:bg-[#111] rounded-xl border border-gray-300 dark:border-gray-800 flex-1 shadow-inner overflow-y-auto p-6 custom-scrollbar">
                    <div class="flex flex-col gap-8 items-center">
                        @foreach($previewImages as $index => $image)
                            <div class="relative w-full max-w-2xl bg-white dark:bg-[#2a2a2a] p-2 rounded shadow-xl">
                                <span class="absolute -top-3 -left-3 bg-red-600 text-white text-[10px] px-3 py-1 rounded-full font-bold shadow-lg z-10">Hal {{ $index + 1 }}</span>
                                <img src="{{ $image }}" class="w-full h-auto object-contain shadow-sm" alt="Halaman {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-800 w-full xl:w-[420px] flex flex-col">
                    <div class="text-center border-b-2 border-dashed pb-4 mb-4">
                        <h3 class="font-black text-lg text-[#cc0000] tracking-tight">{{ $selectedPaper }}</h3>
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-tighter">Receipt Analysis #{{ date('YmdHi') }}</p>
                    </div>

                    <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                        <table class="w-full text-xs">
                            <thead class="text-gray-400 uppercase text-[9px] border-b">
                                <tr>
                                    <th class="py-2 text-left">Halaman</th>
                                    <th class="py-2 text-center">Tinta (%)</th>
                                    <th class="py-2 text-right">Harga</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($analysisResults as $res)
                                
                                    @php
                                        $pct = (float) $res['percentage'];
                                        $badgeClass = 'bg-green-100 text-green-700'; // <= 25% (Hemat)
                                        if($pct > 25 && $pct <= 50) $badgeClass = 'bg-yellow-100 text-yellow-700'; // <= 50% (Sedang)
                                        elseif($pct > 50 && $pct <= 75) $badgeClass = 'bg-orange-100 text-orange-700'; // <= 75% (Tinggi)
                                        elseif($pct > 75) $badgeClass = 'bg-red-100 text-red-700'; // > 75% (Full Block)
                                    @endphp

                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                        <td class="py-4 text-left font-bold text-gray-500">P{{ $res['page'] }}</td>
                                        <td class="py-4 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $badgeClass }}">
                                                {{ $res['percentage'] }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-right font-black text-gray-800 dark:text-gray-200">Rp {{ number_format($res['price'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 pt-4 border-t-2 border-black dark:border-white">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-xs font-black uppercase">Grand Total</span>
                            <span class="text-2xl font-black text-[#cc0000]">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                        <button wire:click="resetView" class="w-full bg-[#cc0000] hover:bg-red-700 text-white font-black py-4 rounded-xl shadow-lg shadow-red-500/30 transition-all active:scale-95">
                            BUAT TRANSAKSI BARU
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>