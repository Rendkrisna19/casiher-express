<main class="flex-1 overflow-y-auto p-4 sm:p-8 animate-fade-in custom-scrollbar">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 flex justify-between items-start shadow-sm transition-all hover:shadow-lg hover:border-blue-500/30 group">
            <div class="flex gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600 shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="w-full">
                    <p class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">Saldo Bawah Hari Ini</p>
                    <h2 class="text-3xl font-black mt-1 text-gray-900 dark:text-white tracking-tight">Rp {{ number_format($saldoBawahHariIni, 0, ',', '.') }}</h2>
                    <p class="text-[10px] text-blue-500 font-bold mt-1 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Diperbarui otomatis hari ini
                    </p>

                    @if($penjualanPrintHariIni > 0)
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Ekstra: Print Warna (Rp {{ number_format($penjualanPrintHariIni, 0, ',', '.') }})</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 flex flex-col justify-between shadow-sm transition-all hover:shadow-lg group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 rounded-2xl bg-red-500/10 flex items-center justify-center text-[#cc0000] shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">Rincian Pengeluaran</p>
                    <h2 class="text-2xl font-black mt-1 text-gray-900 dark:text-white tracking-tight">Rp {{ number_format($totalUA + $totalUB + $totalUR, 0, ',', '.') }}</h2>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 w-full mt-2">
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 p-2.5 rounded-xl text-center">
                    <span class="block text-[10px] font-black text-red-600 mb-0.5">UA (Atas)</span>
                    <span class="block text-xs font-bold text-gray-800 dark:text-gray-200">{{ number_format($totalUA, 0, ',', '.') }}</span>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-100 dark:border-yellow-900/30 p-2.5 rounded-xl text-center">
                    <span class="block text-[10px] font-black text-yellow-600 mb-0.5">UB (Bawah)</span>
                    <span class="block text-xs font-bold text-gray-800 dark:text-gray-200">{{ number_format($totalUB, 0, ',', '.') }}</span>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/30 p-2.5 rounded-xl text-center">
                    <span class="block text-[10px] font-black text-indigo-600 mb-0.5">UR (Receh)</span>
                    <span class="block text-xs font-bold text-gray-800 dark:text-gray-200">{{ number_format($totalUR, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm" wire:ignore>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">Lalu Lintas Saldo & Pengeluaran</h3>
                <p class="text-xs text-gray-500 font-medium mt-1">Statistik pergerakan finansial 7 hari terakhir.</p>
            </div>
            <div class="flex gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 dark:bg-[#181818] rounded-lg border border-gray-100 dark:border-gray-800">
                    <div class="w-3 h-3 rounded-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>
                    <span class="text-[10px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Saldo Bawah</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 dark:bg-[#181818] rounded-lg border border-gray-100 dark:border-gray-800">
                    <div class="w-3 h-3 rounded-full bg-[#cc0000] shadow-sm shadow-red-500/50"></div>
                    <span class="text-[10px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Pengeluaran</span>
                </div>
            </div>
        </div>
        
        <div id="dailyChart" class="w-full"></div>
    </div>

<script>
    function initChart() {
        const chartElement = document.querySelector("#dailyChart");
        if (!chartElement) return;

        chartElement.innerHTML = ''; 

        const isDark = document.documentElement.classList.contains('dark');
        
        const options = {
            series: [{
                name: 'Saldo Bawah',
                data: @json($chartSaldoBawah) // Menggunakan data 7 Hari Terakhir
            }, {
                name: 'Pengeluaran (UA+UB+UR)',
                data: @json($chartTotalPengeluaran) // Menggunakan data 7 Hari Terakhir
            }],
            chart: {
                type: 'area',
                height: 380,
                toolbar: { show: false },
                fontFamily: 'inherit',
                sparkline: { enabled: false },
            },
            colors: ['#3b82f6', '#cc0000'], 
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    inverseColors: false,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100]
                }
            },
            xaxis: {
                categories: @json($chartDates), // Otomatis mengisi tanggal hari ini mundur 7 hari
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: isDark ? '#6b7280' : '#9ca3af', fontSize: '12px', fontWeight: 600 } }
            },
            yaxis: {
                labels: {
                    formatter: function (val) { 
                        if (val >= 1000000) return "Rp " + (val / 1000000).toFixed(1) + "jt"; 
                        else if (val >= 1000) return "Rp " + (val / 1000).toFixed(0) + "k"; 
                        return "Rp " + val;
                    },
                    style: { colors: isDark ? '#6b7280' : '#9ca3af', fontWeight: 600 }
                }
            },
            grid: {
                borderColor: isDark ? '#374151' : '#f3f4f6',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: 0, bottom: 0, left: 10 }
            },
            legend: { show: false },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                y: { formatter: function (val) { return "Rp " + val.toLocaleString('id-ID'); } }
            },
            markers: { size: 4, hover: { size: 6, strokeWidth: 3 } }
        };

        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }

    document.addEventListener('livewire:initialized', initChart);
    document.addEventListener('livewire:navigated', initChart);
    window.addEventListener('theme-changed', initChart);
</script>
</main>