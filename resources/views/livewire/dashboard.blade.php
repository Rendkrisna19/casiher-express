<main class="flex-1 overflow-y-auto p-4 sm:p-8 animate-fade-in custom-scrollbar">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 flex justify-between items-start shadow-sm transition-all hover:shadow-lg hover:border-blue-500/30 group">
            <div class="flex gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600 shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">Hasil Penjualan (HP)</p>
                    <h2 class="text-3xl font-black mt-1 text-gray-900 dark:text-white tracking-tight">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h2>
                    <p class="text-[10px] text-blue-500 font-bold mt-1 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Data realtime hari ini
                    </p>
                </div>
            </div>
            <span class="bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 text-[10px] px-3 py-1 rounded-full font-black tracking-wider">INCOME</span>
        </div>

        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 flex justify-between items-start shadow-sm transition-all hover:shadow-lg hover:border-[#cc0000]/30 group">
            <div class="flex gap-4">
                <div class="w-14 h-14 rounded-2xl bg-red-500/10 flex items-center justify-center text-[#cc0000] shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">Total Pengeluaran</p>
                    <h2 class="text-3xl font-black mt-1 text-gray-900 dark:text-white tracking-tight">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
                    <p class="text-[10px] text-red-500 font-bold mt-1 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Berdasarkan rincian input
                    </p>
                </div>
            </div>
            <span class="bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 text-[10px] px-3 py-1 rounded-full font-black tracking-wider">OUTCOME</span>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm" wire:ignore>
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">Analisis Pemasukan & Pengeluaran</h3>
                <p class="text-xs text-gray-500 font-medium mt-1">Statistik pergerakan finansial tahun {{ now()->year }}</p>
            </div>
            <div class="flex gap-3">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 dark:bg-[#181818] rounded-lg border border-gray-100 dark:border-gray-800">
                    <div class="w-3 h-3 rounded-full bg-blue-500 shadow-sm shadow-blue-500/50"></div>
                    <span class="text-[10px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Penjualan</span>
                </div>
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 dark:bg-[#181818] rounded-lg border border-gray-100 dark:border-gray-800">
                    <div class="w-3 h-3 rounded-full bg-[#cc0000] shadow-sm shadow-red-500/50"></div>
                    <span class="text-[10px] font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Pengeluaran</span>
                </div>
            </div>
        </div>
        
        <div id="salesChart" class="w-full"></div>
    </div>

<script>
    function initChart() {
        const chartElement = document.querySelector("#salesChart");
        if (!chartElement) return;

        chartElement.innerHTML = ''; // Clear previous instance

        const isDark = document.documentElement.classList.contains('dark');
        
        const options = {
            series: [{
                name: 'Hasil Penjualan (HP)',
                data: @json($monthlySales) // Data Real dari DB
            }, {
                name: 'Pengeluaran',
                data: @json($monthlyExpenses) // Data Real dari DB (Bukan simulasi lagi)
            }],
            chart: {
                type: 'area',
                height: 380,
                toolbar: { show: false },
                fontFamily: 'inherit',
                sparkline: { enabled: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            // Warna disesuaikan dengan tema: Biru (Sales) & Merah Gelap (Pengeluaran)
            colors: ['#3b82f6', '#cc0000'], 
            dataLabels: { enabled: false },
            stroke: { 
                curve: 'smooth', 
                width: 3 
            },
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
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { 
                    style: { 
                        colors: isDark ? '#6b7280' : '#9ca3af', 
                        fontSize: '12px',
                        fontWeight: 600
                    } 
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) { 
                        if (val >= 1000000) {
                            return "Rp " + (val / 1000000).toFixed(1) + "jt"; 
                        } else if (val >= 1000) {
                            return "Rp " + (val / 1000).toFixed(0) + "k"; 
                        }
                        return "Rp " + val;
                    },
                    style: { 
                        colors: isDark ? '#6b7280' : '#9ca3af',
                        fontWeight: 600
                    }
                }
            },
            grid: {
                borderColor: isDark ? '#374151' : '#f3f4f6',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: 0, bottom: 0, left: 10 }
            },
            legend: {
                show: false 
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                x: { show: true },
                y: {
                    formatter: function (val) {
                        return "Rp " + val.toLocaleString('id-ID');
                    }
                }
            },
            markers: {
                size: 0,
                hover: { size: 6, strokeWidth: 3 }
            }
        };

        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }

    // Eksekusi chart saat halaman dimuat atau Livewire navigasi
    document.addEventListener('livewire:initialized', initChart);
    document.addEventListener('livewire:navigated', initChart);
    window.addEventListener('theme-changed', initChart);
</script>
</main>