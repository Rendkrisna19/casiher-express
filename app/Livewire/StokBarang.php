<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Barang;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StokBarang extends Component
{
    use WithPagination;

    public $barangId, $kode_barang, $nama_barang, $harga, $stok;
    public $isOpen = false;
    public $search = '';

    public function render() {
        $barangs = Barang::where('nama_barang', 'like', '%'.$this->search.'%')
                        ->orWhere('kode_barang', 'like', '%'.$this->search.'%')
                        ->latest()->paginate(10);

        return view('livewire.stok-barang', [
            'barangs' => $barangs
        ]);
    }

    public function create() {
        $this->resetInputFields();
        
        // Auto-generate random barcode string (Misal: BRG-583021)
        // User tetap bisa menghapus inputan ini dan menggantinya manual
        $this->kode_barang = 'BRG-' . mt_rand(100000, 999999);
        
        $this->openModal();
    }

    public function openModal() { $this->isOpen = true; }
    public function closeModal() { $this->isOpen = false; }

    private function resetInputFields(){
        $this->kode_barang = '';
        $this->nama_barang = '';
        $this->harga = '';
        $this->stok = 0;
        $this->barangId = '';
    }

    public function store() {
        // Jika user mengosongkan form, kita buatin lagi kodenya secara paksa
        if (empty($this->kode_barang)) {
            $this->kode_barang = 'BRG-' . mt_rand(100000, 999999);
        }

        $this->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,'.$this->barangId,
            'nama_barang' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
        ]);

        $isUpdate = $this->barangId ? true : false;

        Barang::updateOrCreate(['id' => $this->barangId], [
            'kode_barang' => strtoupper($this->kode_barang), // Memastikan kode selalu huruf besar
            'nama_barang' => $this->nama_barang,
            'harga' => $this->harga,
            'stok' => $this->stok,
        ]);

        $this->dispatch('swal', 
            title: 'Berhasil!', 
            text: $isUpdate ? 'Data Barang Berhasil Diperbarui.' : 'Data Barang Baru Disimpan.', 
            icon: 'success'
        );
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id) {
        $barang = Barang::findOrFail($id);
        $this->barangId = $id;
        $this->kode_barang = $barang->kode_barang;
        $this->nama_barang = $barang->nama_barang;
        $this->harga = $barang->harga;
        $this->stok = $barang->stok;
        $this->openModal();
    }

    public function delete($id) {
        Barang::find($id)->delete();
        
        $this->dispatch('swal', 
            title: 'Dihapus!', 
            text: 'Barang telah berhasil dibuang dari sistem.', 
            icon: 'success'
        );
    }
}