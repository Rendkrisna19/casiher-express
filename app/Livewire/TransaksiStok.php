<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Barang;
use App\Models\StokLog;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class TransaksiStok extends Component
{
    use WithFileUploads;

    public $searchCode;
    public $barangTerpilih;
    public $jumlah = 1;
    public $jenis = 'masuk';
    public $keterangan;
    public $bukti;

    // Fungsi otomatis saat Barcode terdeteksi oleh JS/Upload
    public function updatedSearchCode($value)
    {
        $this->barangTerpilih = Barang::where('kode_barang', $value)->first();
        
        if (!$this->barangTerpilih) {
            // Trigger SweetAlert Gagal
            $this->dispatch('swal', [
                'title' => 'Barang Tidak Ditemukan!',
                'text' => 'Kode ' . $value . ' belum terdaftar di sistem.',
                'icon' => 'error'
            ]);
            $this->reset('barangTerpilih'); 
        } else {
            // Trigger SweetAlert Berhasil Deteksi
            $this->dispatch('swal', [
                'title' => 'Terdeteksi!',
                'text' => $this->barangTerpilih->nama_barang,
                'icon' => 'success',
                'timer' => 1500 // Akan tertutup otomatis dalam 1.5 detik
            ]);
            
            $this->jumlah = 1;
            $this->jenis = 'masuk';
        }
    }

    public function simpanTransaksi()
    {
        if (!$this->barangTerpilih) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Silakan scan barang terlebih dahulu.',
                'icon' => 'warning'
            ]);
            return;
        }

        $this->validate([
            'jumlah' => 'required|numeric|min:1',
            'jenis' => 'required|in:masuk,keluar',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($this->jenis === 'keluar' && $this->barangTerpilih->stok < $this->jumlah) {
            $this->dispatch('swal', [
                'title' => 'Stok Kurang!',
                'text' => 'Sisa stok tidak cukup untuk melakukan transaksi keluar.',
                'icon' => 'error'
            ]);
            return;
        }

        $path = $this->bukti ? $this->bukti->store('bukti-stok', 'public') : null;

        StokLog::create([
            'barang_id' => $this->barangTerpilih->id,
            'jenis' => $this->jenis,
            'jumlah' => $this->jumlah,
            'keterangan' => $this->keterangan,
            'bukti_transaksi' => $path
        ]);

        if ($this->jenis === 'masuk') {
            $this->barangTerpilih->increment('stok', $this->jumlah);
        } else {
            $this->barangTerpilih->decrement('stok', $this->jumlah);
        }

        // Trigger SweetAlert Sukses Simpan Data
        $this->dispatch('swal', [
            'title' => 'Transaksi Berhasil!',
            'text' => 'Stok ' . $this->barangTerpilih->nama_barang . ' telah berhasil diperbarui.',
            'icon' => 'success'
        ]);

        // Kembalikan form ke posisi standby
        $this->reset(['barangTerpilih', 'searchCode', 'jumlah', 'keterangan', 'bukti']);
    }

    public function render() {
        return view('livewire.transaksi-stok');
    }
}