<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\TransaksiKasir;
use Carbon\Carbon;

#[Layout('layouts.app')]
class HitungPenghasilan extends Component
{
    public $transaksiId;

    // Variabel Form Utama
    public $sisa_kasir = 0; // Input Baru pengganti manual HP
    public $saldo_sebelum = 0;
    public $saldo_masuk_rek = 0;
    public $setor_tunai = 0;
    public $kas_masuk = 0;
    public $saldo_rek = 0;
    public $pengeluaranItems = [];
    
    // Variabel Form Modal Pengeluaran
    public $new_nama_transaksi = '';
    public $new_price = 0;
    public $new_jenis = 'UA'; // Default Dropdown UA, UB, UR
    public $new_keterangan = '';

    public function mount()
    {
        $data = TransaksiKasir::latest()->first();

        if ($data) {
            $this->transaksiId = $data->id;
            $this->sisa_kasir = $data->sisa_kasir ?? 0;
            $this->saldo_sebelum = $data->saldo_sebelum;
            $this->saldo_masuk_rek = $data->saldo_masuk_rek;
            $this->setor_tunai = $data->setor_tunai;
            $this->kas_masuk = $data->kas_masuk;
            $this->saldo_rek = $data->saldo_rek;
            $this->pengeluaranItems = $data->pengeluaran_items ?? [];
        }
    }

    // Menghitung Total Pengeluaran Khusus UA (Untuk Rumus HP)
   public function getTotalUAProperty()
    {
        return array_sum(array_column(array_filter($this->pengeluaranItems, function($item) {
            // Tambahkan ?? 'UA' agar data lama yang tidak punya 'jenis' tidak error
            // dan otomatis dianggap sebagai pengeluaran Kasir (UA)
            return ($item['jenis'] ?? 'UA') === 'UA'; 
        }), 'price'));
    }

    // Menghitung Total Semua Pengeluaran
    public function getTotalPengeluaranProperty()
    {
        return array_sum(array_column($this->pengeluaranItems, 'price'));
    }

    // RUMUS 1: HP (Hasil Penjualan) = Sisa Kasir + Pengeluaran(UA) + Saldo Masuk Rek
    public function getHasilPenjualanProperty()
    {
        return (int)$this->sisa_kasir + $this->totalUA + (int)$this->saldo_masuk_rek;
    }

    // RUMUS 2: Saldo Bawah = Saldo Sebelum + HP - Pengeluaran Keseluruhan - Setor Tunai - Saldo Masuk Rek (+ Kas Masuk)
    public function getSaldoBawahProperty()
    {
        return ((int)$this->saldo_sebelum + $this->hasilPenjualan + (int)$this->kas_masuk) 
                - $this->totalPengeluaran 
                - (int)$this->setor_tunai 
                - (int)$this->saldo_masuk_rek;
    }

    public function autoSave()
    {
        $transaksi = TransaksiKasir::updateOrCreate(
            ['id' => $this->transaksiId],
            [
                'sisa_kasir' => $this->sisa_kasir,
                'hasil_penjualan' => $this->hasilPenjualan, // Disimpan otomatis hasil perhitungannya
                'saldo_sebelum' => $this->saldo_sebelum,
                'saldo_masuk_rek' => $this->saldo_masuk_rek,
                'setor_tunai' => $this->setor_tunai,
                'kas_masuk' => $this->kas_masuk,
                'saldo_rek' => $this->saldo_rek,
                'pengeluaran_items' => $this->pengeluaranItems,
            ]
        );

        $this->transaksiId = $transaksi->id;

        $this->dispatch('swal', [
            'title' => 'Tersimpan!',
            'text' => 'Data berhasil diperbarui di database.',
            'icon' => 'success'
        ]);
    }

    public function addPengeluaran()
    {
        if($this->new_nama_transaksi && $this->new_price) {
            $this->pengeluaranItems[] = [
                'nama' => $this->new_nama_transaksi,
                'price' => (int)$this->new_price,
                'jenis' => $this->new_jenis,
                'keterangan' => $this->new_keterangan
            ];
            
            // Reset form modal
            $this->reset(['new_nama_transaksi', 'new_price', 'new_keterangan']);
            $this->new_jenis = 'UA'; // Kembalikan ke default
            
            $this->autoSave();
        }
    }

    public function removePengeluaran($index)
    {
        unset($this->pengeluaranItems[$index]);
        $this->pengeluaranItems = array_values($this->pengeluaranItems);
        $this->autoSave();
    }

    public function render()
    {
        return view('livewire.hitung-penghasilan', [
            'tanggal_transaksi' => Carbon::now()->format('d - M - Y'),
            'waktu_transaksi' => Carbon::now()->format('H:i')
        ]);
    }
}