<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PrintColorAnalysis extends Component
{
    use WithFileUploads;

    public $currentView = 'menu';
    public $selectedPaper = '';
    public $documentFile;

    public $previewImage = null;
    public $analysisResults = [];
    public $totalPrice = 0;

    public $paperTypes = [
        'Print A4/F4 Warna 70 gsm' => ['price_base' => 1000],
        'Print A4/F4 Warna 80 gsm' => ['price_base' => 1200],
        'Print Buffalo Warna' => ['price_base' => 2000],
        'Print A4/F4 Warna 70 gsm Bolak Balik' => ['price_base' => 1800],
        'Print A4/F4 Warna 80 gsm Bolak Balik' => ['price_base' => 2200],
        'Print Buffalo Warna Bolak balik' => ['price_base' => 3800],
    ];

    public function selectPaper($paperName)
    {
        $this->selectedPaper = $paperName;
        $this->currentView = 'upload';
    }

    public function updatedDocumentFile()
    {
        $this->validate([
            'documentFile' => 'required|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $this->analyzeDocument();
    }

    public function analyzeDocument()
    {
        $filePath = $this->documentFile->getRealPath();

        $this->analysisResults = [];
        $this->totalPrice = 0;

        try {
            // --- FIX PATH ABSOLUT UNTUK WINDOWS ---
            $gsPath = 'C:\PROGRA~1\gs\gs10.06.0\bin\gs.exe';
            // Daftarkan jalur GS ke semua kemungkinan variabel lingkungan yang dibaca Imagick
            putenv("GS_PROG=" . $gsPath);
            putenv("MAGICK_GHOSTSCRIPT_PATH=" . 'C:\PROGRA~1\gs\gs10.06.0\bin');

            // Inisialisasi Imagick
            $imagick = new \Imagick();

            // Set resolusi SEBELUM membaca file (Penting untuk PDF)
            $imagick->setResolution(100, 100);

            // Baca file
            $imagick->readImage($filePath);

            $pagesCount = $imagick->getNumberImages();
            $basePrice = $this->paperTypes[$this->selectedPaper]['price_base'];

            for ($i = 0; $i < $pagesCount; $i++) {
                $imagick->setIteratorIndex($i);

                if ($i === 0) {
                    $preview = clone $imagick;
                    $preview->setImageFormat('png');
                    $this->previewImage = 'data:image/png;base64,' . base64_encode($preview->getImageBlob());
                    $preview->clear();
                }

                $analyzeImg = clone $imagick;
                $analyzeImg->resizeImage(100, 100, \Imagick::FILTER_LANCZOS, 1);

                $pixels = $analyzeImg->getImageHistogram();
                $colorPixelCount = 0;
                $totalPixels = 100 * 100;

                foreach ($pixels as $pixel) {
                    $color = $pixel->getColor();
                    if ($color['r'] < 250 || $color['g'] < 250 || $color['b'] < 250) {
                        $colorPixelCount += $pixel->getColorCount();
                    }
                }

                $percentage = ($colorPixelCount / $totalPixels) * 100;
                $calculatedPrice = $basePrice;

                if ($percentage > 50) {
                    $calculatedPrice += 500;
                } elseif ($percentage < 5) {
                    $calculatedPrice = $basePrice * 0.5;
                }

                $this->analysisResults[] = [
                    'page' => $i + 1,
                    'percentage' => round($percentage) . ' %',
                    'price' => $calculatedPrice
                ];

                $this->totalPrice += $calculatedPrice;
                $analyzeImg->clear();
            }

            $imagick->clear();
            $this->currentView = 'result';
        } catch (\Exception $e) {
            // Menampilkan pesan error yang lebih detail untuk debugging
            $this->addError('documentFile', 'Gagal memproses file. Pesan: ' . $e->getMessage());
        }
    }

    public function resetView()
    {
        $this->reset(['currentView', 'selectedPaper', 'documentFile', 'previewImage', 'analysisResults', 'totalPrice']);
    }

    public function render()
    {
        return view('livewire.print-color-analysis');
    }
}
