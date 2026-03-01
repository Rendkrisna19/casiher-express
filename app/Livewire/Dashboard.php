<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransaksiKasir;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        // Ambil data hari ini
        $hariIni = TransaksiKasir::whereDate('created_at', now())->latest()->first();
        
        // Ambil data 12 bulan terakhir untuk Chart
        $chartData = TransaksiKasir::select(
            DB::raw('SUM(hasil_penjualan) as total_penjualan'),
            DB::raw('MONTH(created_at) as bulan')
        )
        ->whereYear('created_at', now()->year)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        // Format data untuk Chart (mengisi bulan yang kosong dengan 0)
        $monthlySales = array_fill(0, 12, 0);
        foreach ($chartData as $data) {
            $monthlySales[$data->bulan - 1] = (int) $data->total_penjualan;
        }

        return view('livewire.dashboard', [
            'totalPenjualan' => $hariIni->hasil_penjualan ?? 0,
            'totalPengeluaran' => $hariIni ? array_sum(array_column($hariIni->pengeluaran_items ?? [], 'price')) : 0,
            'monthlySales' => $monthlySales
        ]);
    }
}