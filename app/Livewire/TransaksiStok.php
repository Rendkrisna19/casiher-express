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
    public $searchManual; 
    public $searchResults = []; 

    public $barangTerpilih;
    public $jumlah = 1;
    public $jenis = 'masuk';
    public $keterangan;
    public $bukti;

    public function updatedSearchManual($value)
    {
        if (strlen($value) >= 2) {
            $this->searchResults = Barang::where('nama_barang', 'like', '%' . $value . '%')
                ->orWhere('kode_barang', 'like', '%' . $value . '%')
                ->limit(5)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function pilihBarangManual($id)
    {
        $this->barangTerpilih = Barang::find($id);
        $this->searchManual = '';
        $this->searchResults = [];
        
        $this->siapkanForm();
        
        $this->dispatch('swal', [
            'title' => 'Terpilih!',
            'text' => $this->barangTerpilih->nama_barang,
            'icon' => 'success'
        ]);
    }

    public function updatedSearchCode($value)
    {
        $this->barangTerpilih = Barang::where('kode_barang', $value)->first();
        
        if (!$this->barangTerpilih) {
            $this->dispatch('swal', [
                'title' => 'Tidak Ditemukan!',
                'text' => 'Kode SKU ' . $value . ' tidak terdaftar di sistem.',
                'icon' => 'error'
            ]);
            $this->reset('barangTerpilih', 'searchCode'); 
        } else {
            $this->siapkanForm();
            
            $this->dispatch('swal', [
                'title' => 'Terdeteksi!',
                'text' => $this->barangTerpilih->nama_barang,
                'icon' => 'success'
            ]);
        }
        
        $this->searchCode = ''; 
    }

    private function siapkanForm()
    {
        $this->jumlah = 1;
        $this->jenis = 'masuk';
        $this->resetValidation();
    }

    public function simpanTransaksi()
    {
        if (!$this->barangTerpilih) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Silakan scan atau pilih barang terlebih dahulu.',
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
                'text' => 'Sisa stok (' . $this->barangTerpilih->stok . ') tidak cukup untuk dikeluarkan.',
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

        // PERUBAHAN: Gunakan swal-reload agar frontend tahu harus auto-reload
        $this->dispatch('swal-reload', [
            'title' => 'Transaksi Berhasil!',
            'text' => 'Stok ' . $this->barangTerpilih->nama_barang . ' telah diperbarui.',
            'icon' => 'success'
        ]);

        $this->reset(['barangTerpilih', 'searchCode', 'searchManual', 'searchResults', 'jumlah', 'keterangan', 'bukti']);
    }

    public function render() {
        return view('livewire.transaksi-stok');
    }
}