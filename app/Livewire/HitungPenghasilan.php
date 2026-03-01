<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\TransaksiKasir;
use Carbon\Carbon;

#[Layout('layouts.app')]
class HitungPenghasilan extends Component
{
    public $viewMode = 'main'; 
    public $transaksiId; // Menyimpan ID transaksi aktif

    // Variabel Form
    public $hasil_penjualan = 0;
    public $saldo_sebelum = 0;
    public $saldo_masuk_rek = 0;
    public $setor_tunai = 0;
    public $kas_masuk = 0;
    public $saldo_rek = 0;
    public $pengeluaranItems = [];
    
    public $new_nama_transaksi = '';
    public $new_price = 0;
    public $new_keterangan = '';

    public function mount()
    {
        // Ambil data terakhir dari database agar tidak kembali ke nol
        $data = TransaksiKasir::latest()->first();

        if ($data) {
            $this->transaksiId = $data->id;
            $this->hasil_penjualan = $data->hasil_penjualan;
            $this->saldo_sebelum = $data->saldo_sebelum;
            $this->saldo_masuk_rek = $data->saldo_masuk_rek;
            $this->setor_tunai = $data->setor_tunai;
            $this->kas_masuk = $data->kas_masuk;
            $this->saldo_rek = $data->saldo_rek;
            $this->pengeluaranItems = $data->pengeluaran_items ?? [];
        }
    }

    public function getTotalPengeluaranProperty()
    {
        return array_sum(array_column($this->pengeluaranItems, 'price'));
    }

    public function getSaldoBawahProperty()
    {
        return ($this->hasil_penjualan + $this->saldo_sebelum + $this->kas_masuk) 
                - $this->totalPengeluaran - $this->saldo_rek;
    }

    public function setMode($mode)
    {
        $this->viewMode = $mode;
    }

    // FUNGSI SIMPAN REAL KE DATABASE
    public function autoSave()
    {
        // Update data jika sudah ada, atau buat baru jika belum ada
        $transaksi = TransaksiKasir::updateOrCreate(
            ['id' => $this->transaksiId],
            [
                'hasil_penjualan' => $this->hasil_penjualan,
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
                'price' => $this->new_price,
                'keterangan' => $this->new_keterangan
            ];
            $this->reset(['new_nama_transaksi', 'new_price', 'new_keterangan']);
            
            // Langsung simpan ke DB agar tidak hilang
            $this->autoSave();
        }
    }

    public function removePengeluaran($index)
    {
        unset($this->pengeluaranItems[$index]);
        $this->pengeluaranItems = array_values($this->pengeluaranItems);
        
        // Langsung simpan ke DB agar tidak hilang
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