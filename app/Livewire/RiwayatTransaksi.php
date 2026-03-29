<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StokLog;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class RiwayatTransaksi extends Component
{
    use WithPagination;

    // Properti untuk Filter & Pencarian
    public $search = '';
    public $tgl_awal = '';
    public $tgl_akhir = '';
    public $jenis_filter = ''; // 'masuk', 'keluar', atau kosong untuk semua

    // Reset pagination jika filter berubah
    public function updatedSearch() { $this->resetPage(); }
    public function updatedTglAwal() { $this->resetPage(); }
    public function updatedTglAkhir() { $this->resetPage(); }
    public function updatedJenisFilter() { $this->resetPage(); }

    public function resetFilter()
    {
        $this->reset(['search', 'tgl_awal', 'tgl_akhir', 'jenis_filter']);
        $this->resetPage();
    }

    public function render()
    {
        $query = StokLog::with('barang');

        // 1. Filter Pencarian (Cari Nama Barang, Kode Barang, atau Keterangan)
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('keterangan', 'like', '%' . $this->search . '%')
                  ->orWhereHas('barang', function($subQ) {
                      $subQ->where('nama_barang', 'like', '%' . $this->search . '%')
                           ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // 2. Filter Tanggal Awal & Akhir
        if (!empty($this->tgl_awal)) {
            $query->whereDate('created_at', '>=', $this->tgl_awal);
        }
        if (!empty($this->tgl_akhir)) {
            $query->whereDate('created_at', '<=', $this->tgl_akhir);
        }

        // 3. Filter Jenis Transaksi
        if (!empty($this->jenis_filter)) {
            $query->where('jenis', $this->jenis_filter);
        }

        // Urutkan dari yang terbaru dan Pagination (10 per halaman)
        $logs = $query->latest()->paginate(10);

        return view('livewire.riwayat-transaksi', [
            'logs' => $logs
        ]);
    }
}