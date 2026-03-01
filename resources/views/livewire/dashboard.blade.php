<main class="flex-1 overflow-y-auto p-4 sm:p-8 animate-fade-in">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 flex justify-between items-start shadow-sm transition-all hover:shadow-md">
            <div class="flex gap-4">
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600 shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Hasil Penjualan (HP)</p>
                    <h2 class="text-2xl font-black mt-1 text-gray-900 dark:text-white">Rp. {{ number_format($totalPenjualan, 0, ',', '.') }}</h2>
                    <p class="text-[10px] text-green-500 font-bold mt-1">● Terakhir diupdate hari ini</p>
                </div>
            </div>
            <span class="bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 text-[10px] px-2 py-1 rounded-md font-bold">REALTIME</span>
        </div>

        <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 flex justify-between items-start shadow-sm transition-all hover:shadow-md">
            <div class="flex gap-4">
                <div class="w-14 h-14 rounded-2xl bg-red-500/10 flex items-center justify-center text-red-600 shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Total Pengeluaran</p>
                    <h2 class="text-2xl font-black mt-1 text-gray-900 dark:text-white">Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
                    <p class="text-[10px] text-gray-400 mt-1">Berdasarkan rincian input terakhir</p>
                </div>
            </div>
            <span class="bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 text-[10px] px-2 py-1 rounded-md font-bold">OUT</span>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1e1e1e] p-6 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm" wire:ignore>
        <div class="flex justify-between items-center mb-8">
            <div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">Analisis Penjualan</h3>
                <p class="text-xs text-gray-500">Statistik pertumbuhan omzet tahun {{ now()->year }}</p>
            </div>
            <div class="flex gap-3">
                <div class="flex items-center gap-2 px-3 py-1 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                    <span class="text-[10px] font-bold text-gray-600 dark:text-gray-300">Penjualan</span>
                </div>
            </div>
        </div>
        
        <div id="salesChart" class="w-full"></div>
    </div>

 <script>
    function initChart() {
        const chartElement = document.querySelector("#salesChart");
        if (!chartElement) return;

        chartElement.innerHTML = '';

        const isDark = document.documentElement.classList.contains('dark');
        
        const options = {
            series: [{
                name: 'Hasil Penjualan (HP)',
                // Data ini mengambil dari variabel $monthlySales di Controller
                data: @json($monthlySales) 
            }, {
                name: 'Pengeluaran',
                // Simulasi data pengeluaran (Anda bisa sesuaikan di Controller nanti)
                data: [1200000, 1800000, 1500000, 2500000, 3200000, 2800000, 3500000, 4200000, 3800000, 4800000, 4500000, 5200000]
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Poppins, sans-serif',
                sparkline: { enabled: false },
            },
            // Menggunakan warna Kuning dan Merah sesuai gambar
            colors: ['#facc15', '#ef4444'], 
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
                    stops: [20, 100, 100, 100]
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { 
                    style: { 
                        colors: '#9ca3af', 
                        fontSize: '12px',
                        fontWeight: 500
                    } 
                }
            },
            yaxis: {
                labels: {
                    formatter: function (val) { 
                        return "Rp " + (val / 1000000) + "jt"; 
                    },
                    style: { 
                        colors: '#9ca3af',
                        fontWeight: 500
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
                show: false // Kita gunakan legend manual yang sudah ada di HTML atas
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
                hover: { size: 5 }
            }
        };

        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }

    // Listener agar tidak perlu refresh halaman
    document.addEventListener('livewire:initialized', initChart);
    document.addEventListener('livewire:navigated', initChart);
    window.addEventListener('theme-changed', initChart);
</script>
</main>