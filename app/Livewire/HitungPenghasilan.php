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
    public $sisa_kasir = 0; 
    public $saldo_sebelum = 0;
    public $saldo_masuk_rek = 0;
    public $setor_tunai = 0;
    public $kas_masuk = 0; // Hanya sebagai catatan/informasi
    public $saldo_rek = 0;
    public $pengeluaranItems = [];
    
    // Variabel Form Modal
    public $new_nama_transaksi = '';
    public $new_price = 0;
    public $new_jenis = 'UA'; 
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

    public function getTotalUAProperty()
    {
        return array_sum(array_column(array_filter($this->pengeluaranItems, function($item) {
            return ($item['jenis'] ?? 'UA') === 'UA'; 
        }), 'price'));
    }

    public function getTotalPengeluaranProperty()
    {
        return array_sum(array_column($this->pengeluaranItems, 'price'));
    }

    public function getHasilPenjualanProperty()
    {
        return (int)$this->sisa_kasir + (int)$this->totalUA + (int)$this->saldo_masuk_rek;
    }

    // FIX RUMUS SALDO BAWAH: Kas Masuk TIDAK DIMASUKKAN ke perhitungan
    public function getSaldoBawahProperty()
    {
        $pemasukan = (int)$this->saldo_sebelum + (int)$this->hasilPenjualan;
        $pengurang = (int)$this->totalPengeluaran + (int)$this->setor_tunai + (int)$this->saldo_masuk_rek;
        
        return $pemasukan - $pengurang;
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
        $this->dispatch('swal', ['title' => 'Tersimpan!', 'text' => 'Data diperbarui.', 'icon' => 'success']);
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
            $this->reset(['new_nama_transaksi', 'new_price', 'new_keterangan', 'new_jenis']);
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
        $now = Carbon::now('Asia/Jakarta');
        return view('livewire.hitung-penghasilan', [
            'tanggal_transaksi' => $now->translatedFormat('d F Y'),
            'waktu_transaksi' => $now->format('H:i') . ' WIB'
        ]);
    }
}