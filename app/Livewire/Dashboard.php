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
        // ==========================================
        // 1. AMBIL DATA HARI INI
        // ==========================================
        $hariIni = TransaksiKasir::whereDate('created_at', Carbon::today())->latest()->first();
        
        $saldoBawahHariIni = 0;
        $totalUA = 0;
        $totalUB = 0;
        $totalUR = 0;

        if ($hariIni) {
            // Pisahkan pengeluaran berdasarkan kategorinya
            $pengeluaran = $hariIni->pengeluaran_items ?? [];
            foreach ($pengeluaran as $item) {
                $jenis = $item['jenis'] ?? 'UA';
                $price = (int) $item['price'];
                
                if ($jenis === 'UA') $totalUA += $price;
                elseif ($jenis === 'UB') $totalUB += $price;
                elseif ($jenis === 'UR') $totalUR += $price;
            }

            // Hitung Saldo Bawah Hari Ini
            $hp = (int) $hariIni->hasil_penjualan;
            $saldoSebelum = (int) $hariIni->saldo_sebelum;
            $kasMasuk = (int) $hariIni->kas_masuk;
            $setorTunai = (int) $hariIni->setor_tunai;
            $saldoMasukRek = (int) $hariIni->saldo_masuk_rek;

            // Rumus Saldo Bawah (UA memotong 2x efek HP)
            $saldoBawahHariIni = ($saldoSebelum + $hp + $kasMasuk) - ($totalUA * 2) - $setorTunai - $saldoMasukRek;
        }

        // Ambil Data Print (Opsional, jika Anda tetap ingin menampilkannya terpisah)
        $penjualanPrintHariIni = 0;
        if (class_exists('\App\Models\TransaksiPrint')) {
            $penjualanPrintHariIni = \App\Models\TransaksiPrint::whereDate('created_at', Carbon::today())->sum('total_harga');
        }

        // ==========================================
        // 2. AMBIL DATA 7 HARI TERAKHIR UNTUK GRAFIK
        // ==========================================
        $chartDates = [];
        $chartSaldoBawah = [];
        $chartTotalPengeluaran = []; // Gabungan UA + UB + UR per hari

        // Looping 7 hari ke belakang (mulai dari 6 hari lalu sampai hari ini)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->translatedFormat('d M'); // Contoh: "12 Apr"

            // Ambil transaksi di tanggal tersebut
            $transaksiHarian = TransaksiKasir::whereDate('created_at', $date)->latest()->first();
            
            $daySaldoBawah = 0;
            $dayPengeluaran = 0;

            if ($transaksiHarian) {
                $dPengeluaran = $transaksiHarian->pengeluaran_items ?? [];
                $dTotalUA = 0;
                
                foreach ($dPengeluaran as $item) {
                    $jenis = $item['jenis'] ?? 'UA';
                    $price = (int) $item['price'];
                    $dayPengeluaran += $price; // Tambahkan ke total pengeluaran grafik
                    
                    if ($jenis === 'UA') {
                        $dTotalUA += $price;
                    }
                }

                $dHp = (int) $transaksiHarian->hasil_penjualan;
                $dSaldoSebelum = (int) $transaksiHarian->saldo_sebelum;
                $dKasMasuk = (int) $transaksiHarian->kas_masuk;
                $dSetorTunai = (int) $transaksiHarian->setor_tunai;
                $dSaldoMasukRek = (int) $transaksiHarian->saldo_masuk_rek;

                $daySaldoBawah = ($dSaldoSebelum + $dHp + $dKasMasuk) - ($dTotalUA * 2) - $dSetorTunai - $dSaldoMasukRek;
            }

            $chartSaldoBawah[] = $daySaldoBawah;
            $chartTotalPengeluaran[] = $dayPengeluaran;
        }

        return view('livewire.dashboard', [
            'saldoBawahHariIni' => $saldoBawahHariIni,
            'penjualanPrintHariIni' => $penjualanPrintHariIni,
            'totalUA' => $totalUA,
            'totalUB' => $totalUB,
            'totalUR' => $totalUR,
            'chartDates' => $chartDates,
            'chartSaldoBawah' => $chartSaldoBawah,
            'chartTotalPengeluaran' => $chartTotalPengeluaran,
        ]);
    }
}