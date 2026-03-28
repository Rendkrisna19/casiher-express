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
    public $previewImages = []; 
    public $analysisResults = [];
    public $totalPrice = 0;

    // Harga Flat untuk Hitam Putih (Bukan Berwarna)
    public $priceBlackWhite = 200;

    public $paperTypes = [
        'Print A4/F4 Warna 70 gsm' => [
            'tiers' => [25 => 300, 50 => 500, 75 => 800, 100 => 1000]
        ],
        'Print A4/F4 Warna 80 gsm' => [
            'tiers' => [25 => 350, 50 => 600, 75 => 800, 100 => 1000]
        ],
        'Print Buffalo Warna' => [
            'tiers' => [25 => 1000, 50 => 1000, 75 => 1500, 100 => 1500]
        ],
        'Print A4/F4 Warna 70 gsm Bolak Balik' => [
            'tiers' => [25 => 250, 50 => 450, 75 => 750, 100 => 900]
        ],
        'Print A4/F4 Warna 80 gsm Bolak Balik' => [
            'tiers' => [25 => 300, 50 => 550, 75 => 800, 100 => 1000]
        ],
        'Print Buffalo Warna Bolak balik' => [
            'tiers' => [25 => 950, 50 => 950, 75 => 1400, 100 => 1400]
        ],
    ];

    public function selectPaper($paperName)
    {
        $this->selectedPaper = $paperName;
        $this->currentView = 'upload';
    }

    public function updatedDocumentFile()
    {
        $this->validate([
            'documentFile' => 'required|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);

        $this->analyzeDocument();
    }

    public function analyzeDocument() {
        $filePath = $this->documentFile->getRealPath();
        $this->analysisResults = [];
        $this->previewImages = []; 
        $this->totalPrice = 0;

        try {
            $gsPath = 'C:\PROGRA~1\gs\gs10.06.0\bin\gs.exe';
            putenv("GS_PROG=" . $gsPath);
            putenv("MAGICK_GHOSTSCRIPT_PATH=" . 'C:\PROGRA~1\gs\gs10.06.0\bin');

            $ping = new \Imagick();
            $ping->pingImage($filePath);
            $pagesCount = $ping->getNumberImages();
            $ping->clear();
            $ping->destroy();

            $tiers = $this->paperTypes[$this->selectedPaper]['tiers'];

            for ($i = 0; $i < $pagesCount; $i++) {
                $page = new \Imagick();
                $page->setResolution(150, 150);
                $page->readImage($filePath . '[' . $i . ']'); 
                
                $page->setImageFormat('jpg');
                $page->setImageBackgroundColor('white');
                $page->setImageAlphaChannel(\Imagick::ALPHACHANNEL_REMOVE);
                $processedPage = $page->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);

                $this->previewImages[] = 'data:image/jpeg;base64,' . base64_encode($processedPage->getImageBlob());

                $sample = clone $processedPage;
                $sample->resizeImage(100, 100, \Imagick::FILTER_TRIANGLE, 1);
                
                $width = $sample->getImageWidth();
                $height = $sample->getImageHeight();
                $coloredPixels = 0;
                $isColorPage = false; // Flag untuk deteksi warna

                for ($x = 0; $x < $width; $x++) {
                    for ($y = 0; $y < $height; $y++) {
                        $color = $sample->getImagePixelColor($x, $y)->getColor();
                        
                        // 1. Deteksi Tinta (Bukan Putih)
                        if ($color['r'] < 245 || $color['g'] < 245 || $color['b'] < 245) {
                            $coloredPixels++;

                            // 2. Deteksi Apakah Ini Warna atau Hitam Putih
                            // Jika selisih antara R, G, dan B besar, berarti itu warna.
                            // Toleransi 15-20 pixel biasanya cukup untuk menangani kompresi JPG
                            if (!$isColorPage) {
                                $diff1 = abs($color['r'] - $color['g']);
                                $diff2 = abs($color['g'] - $color['b']);
                                $diff3 = abs($color['r'] - $color['b']);

                                if ($diff1 > 20 || $diff2 > 20 || $diff3 > 20) {
                                    $isColorPage = true;
                                }
                            }
                        }
                    }
                }

                $percentage = ($coloredPixels / ($width * $height)) * 100;
                $calculatedPrice = 0;
                $typeLabel = 'Warna';
                
                // --- LOGIKA PENENTUAN HARGA ---
                if (!$isColorPage && $percentage > 0) {
                    // Jika Hitam Putih (Grayscale)
                    $calculatedPrice = $this->priceBlackWhite;
                    $typeLabel = 'Hitam Putih';
                } elseif ($percentage == 0) {
                    // Halaman Kosong
                    $calculatedPrice = 0;
                    $typeLabel = 'Kosong';
                } else {
                    // Jika Berwarna
                    if ($percentage <= 25) {
                        $calculatedPrice = $tiers[25];
                    } elseif ($percentage <= 50) {
                        $calculatedPrice = $tiers[50];
                    } elseif ($percentage <= 75) {
                        $calculatedPrice = $tiers[75];
                    } else {
                        $calculatedPrice = $tiers[100];
                    }
                }

                $this->analysisResults[] = [
                    'page' => $i + 1,
                    'type' => $typeLabel,
                    'percentage' => round($percentage, 1) . ' %',
                    'price' => $calculatedPrice
                ];

                $this->totalPrice += $calculatedPrice;
                
                // Cleanup
                $page->clear(); $page->destroy();
                $processedPage->clear(); $processedPage->destroy();
                $sample->clear(); $sample->destroy();
            }

            $this->currentView = 'result';
            
        } catch (\Exception $e) {
            $this->addError('documentFile', 'Gagal memproses dokumen: ' . $e->getMessage());
        }
    }

    public function resetView()
    {
        $this->reset(['currentView', 'selectedPaper', 'documentFile', 'previewImages', 'analysisResults', 'totalPrice']);
    }

    public function render()
    {
        return view('livewire.print-color-analysis');
    }
}