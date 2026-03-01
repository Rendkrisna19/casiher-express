<div class="w-full relative">
    
    @if($currentView === 'menu')
        <div class="animate-fade-in-up">
            <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-8">Print Color Analysis</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl">
                @foreach($paperTypes as $paper => $details)
                    <button wire:click="selectPaper('{{ $paper }}')" class="bg-white dark:bg-[#1e1e1e] border-2 border-gray-200 dark:border-gray-700 hover:border-[#cc0000] dark:hover:border-[#cc0000] text-gray-700 dark:text-gray-200 rounded-2xl p-8 h-48 flex items-center justify-center text-center font-medium text-lg transition-all hover:shadow-lg group">
                        <span class="group-hover:text-[#cc0000] transition-colors">{!! str_replace('Warna', 'Warna<br>', $paper) !!}</span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    @if($currentView === 'upload')
        <div class="animate-fade-in-up h-[80vh] flex flex-col items-center justify-center text-center">
            
            <h1 class="text-4xl font-black text-black dark:text-white mb-2">{{ $selectedPaper }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-10 max-w-md">
                Kalkulasi harga print warna kertas {{ explode(' ', trim($selectedPaper))[1] ?? '' }} berdasarkan tingkat persentase warna dokumen / gambar
            </p>

            <div wire:loading wire:target="documentFile" class="mb-4">
                <div class="flex items-center gap-2 text-[#cc0000] font-bold">
                    <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Menganalisa Dokumen, mohon tunggu...
                </div>
            </div>

            @error('documentFile') 
                <span class="text-red-500 mb-4 bg-red-100 p-2 rounded">{{ $message }}</span> 
            @enderror

            <div class="relative w-64 h-16 cursor-pointer group" wire:loading.remove wire:target="documentFile">
                <input type="file" wire:model="documentFile" accept=".pdf, .jpg, .jpeg, .png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="absolute inset-0 bg-[#cc0000] group-hover:bg-red-800 text-white font-bold rounded-xl flex items-center justify-center transition-colors shadow-lg shadow-red-500/30">
                    Pilih File
                </div>
            </div>
            
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400" wire:loading.remove wire:target="documentFile">atau jatuhkan File disini (.pdf, .jpg)</p>
            
            <button wire:click="resetView" wire:loading.remove wire:target="documentFile" class="mt-12 text-gray-400 hover:text-[#cc0000] underline text-sm transition-colors">
                Batal & Kembali
            </button>
        </div>
    @endif

    @if($currentView === 'result')
        <div class="animate-fade-in-up">
            <h1 class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-6">Print Color Analysis</h1>

            <div class="flex flex-col xl:flex-row gap-6 h-[75vh]">
                
                <div class="bg-white dark:bg-[#1e1e1e] rounded-xl border border-gray-200 dark:border-gray-800 flex-1 shadow-sm flex items-center justify-center overflow-hidden p-4 relative">
                    <span class="absolute top-4 left-4 bg-gray-800 text-white text-xs px-3 py-1 rounded-full opacity-50 z-10">Preview (Halaman 1)</span>
                    
                    @if($previewImage)
                        <img src="{{ $previewImage }}" alt="Preview" class="max-w-full max-h-full object-contain drop-shadow-lg">
                    @else
                        <p class="text-gray-400 font-medium">Preview Dokumen</p>
                    @endif
                </div>

                <div class="bg-white dark:bg-[#1e1e1e] p-6 sm:p-8 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 w-full xl:w-[450px] text-gray-900 dark:text-gray-100 flex flex-col">
                    
                    <div class="flex justify-center border-b-2 border-gray-300 dark:border-gray-700 pb-4 mb-4">
                        <img src="{{ asset('assets/images/logo.jpg') }}" alt="EXPRESS" class="h-10 object-contain dark:invert">
                    </div>
                    
                    <div class="text-center mb-6">
                        <h3 class="font-bold text-lg text-[#cc0000] dark:text-red-400">{{ $selectedPaper }}</h3>
                        <p class="text-xs text-gray-500">Analisa Penggunaan Tinta</p>
                    </div>

                    <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-300 dark:border-gray-700 text-gray-500 dark:text-gray-400">
                                    <th class="py-2 text-left font-medium">Pages</th>
                                    <th class="py-2 text-center font-medium">Persentase</th>
                                    <th class="py-2 text-right font-medium">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analysisResults as $res)
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <td class="py-2 text-left">{{ $res['page'] }}</td>
                                    <td class="py-2 text-center font-medium">{{ $res['percentage'] }}</td>
                                    <td class="py-2 text-right">Rp. {{ number_format($res['price'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 pt-4 border-t-2 border-gray-900 dark:border-gray-100 flex justify-between items-center">
                        <span class="text-lg font-bold">Total</span>
                        <span class="text-xl font-bold">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>

                    <div class="mt-8">
                        <button wire:click="resetView" class="w-full bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 rounded-md transition shadow-md">
                            Selesai & Kembali
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>