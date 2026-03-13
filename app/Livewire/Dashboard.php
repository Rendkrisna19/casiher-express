<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiKasir;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        // 1. AMBIL DATA HARI INI
        $hariIni = TransaksiKasir::whereDate('created_at', Carbon::today())->latest()->first();
        
        $totalPenjualanHariIni = $hariIni->hasil_penjualan ?? 0;
        $totalPengeluaranHariIni = 0;
        
        if ($hariIni && is_array($hariIni->pengeluaran_items)) {
            $totalPengeluaranHariIni = array_sum(array_column($hariIni->pengeluaran_items, 'price'));
        }

        // 2. AMBIL DATA 1 TAHUN UNTUK GRAFIK (CHART)
        $transaksiTahunIni = TransaksiKasir::whereYear('created_at', now()->year)->get();

        // Siapkan array kosong untuk 12 bulan (Index 0 = Jan, 11 = Des)
        $monthlySales = array_fill(0, 12, 0);
        $monthlyExpenses = array_fill(0, 12, 0);

        foreach ($transaksiTahunIni as $transaksi) {
            // Dapatkan index bulan (1-12 diubah jadi 0-11)
            $bulanIndex = $transaksi->created_at->format('n') - 1;
            
            // Masukkan total penjualan (HP) ke array bulan yang sesuai
            $monthlySales[$bulanIndex] += (int) $transaksi->hasil_penjualan;
            
            // Hitung dan masukkan total pengeluaran ke array bulan yang sesuai
            $pengeluaran = $transaksi->pengeluaran_items ?? [];
            if (is_array($pengeluaran)) {
                $monthlyExpenses[$bulanIndex] += array_sum(array_column($pengeluaran, 'price'));
            }
        }

        return view('livewire.dashboard', [
            'totalPenjualan' => $totalPenjualanHariIni,
            'totalPengeluaran' => $totalPengeluaranHariIni,
            'monthlySales' => $monthlySales,
            'monthlyExpenses' => $monthlyExpenses, // Mengirim data pengeluaran asli ke FE
        ]);
    }
}