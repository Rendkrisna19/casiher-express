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

    // Variabel Form
    public $sisa_kasir = 0;
    public $saldo_sebelum = 0;
    public $saldo_masuk_rek = 0;
    public $setor_tunai = 0;
    public $kas_masuk = 0;
    public $saldo_rek = 0;
    public $pengeluaranItems = [];
    
    // Variabel Form Modal Pengeluaran
    public $new_nama_transaksi = '';
    public $new_price = 0;
    public $new_jenis = 'UA'; // Default UA
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

    // Menghitung Total Pengeluaran Khusus UA 
    public function getTotalUAProperty()
    {
        return array_sum(array_column(array_filter($this->pengeluaranItems, function($item) {
            return ($item['jenis'] ?? 'UA') === 'UA'; 
        }), 'price'));
    }

    // Menghitung Total Semua Pengeluaran (Hanya untuk tampilan visual UI)
    public function getTotalPengeluaranProperty()
    {
        return array_sum(array_column($this->pengeluaranItems, 'price'));
    }

    // RUMUS 1: HP = Sisa Kasir + UA + Saldo Masuk Rek
    public function getHasilPenjualanProperty()
    {
        return (int)$this->sisa_kasir + $this->totalUA + (int)$this->saldo_masuk_rek;
    }

    // RUMUS 2: SALDO BAWAH 
    public function getSaldoBawahProperty()
    {
        // KUNCI PERBAIKAN: 
        // Karena $this->hasilPenjualan sudah mengandung +UA, kita harus menguranginya dengan ($this->totalUA * 2).
        // Ini memastikan Saldo Bawah benar-benar BERKURANG secara nyata setiap kali pengeluaran UA ditambah.
        // Pengeluaran UB dan UR diabaikan di sini sesuai permintaan Anda.
        return ((int)$this->saldo_sebelum + $this->hasilPenjualan + (int)$this->kas_masuk) 
                - ($this->totalUA * 2) 
                - (int)$this->setor_tunai 
                - (int)$this->saldo_masuk_rek;
    }

    public function autoSave()
    {
        $transaksi = TransaksiKasir::updateOrCreate(
            ['id' => $this->transaksiId],
            [
                'sisa_kasir' => $this->sisa_kasir,
                'hasil_penjualan' => $this->hasilPenjualan,
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
            'text' => 'Data berhasil diperbarui.',
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
            
            $this->reset(['new_nama_transaksi', 'new_price', 'new_keterangan']);
            $this->new_jenis = 'UA'; 
            
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
            'tanggal_transaksi' => Carbon::now()->format('d F Y'),
            'waktu_transaksi' => Carbon::now()->format('H:i') . ' WIB'
        ]);
    }
}