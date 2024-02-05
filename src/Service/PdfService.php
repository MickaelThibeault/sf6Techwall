<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{

    private $domPdf;
    private $pdfOptions;
    public function __construct()
    {
        $this->domPdf = new Dompdf();
        $this->pdfOptions = new Options();
        $this->pdfOptions->set('defaultFont', 'Arial');
        $this->domPdf->setOptions($this->pdfOptions);
    }

    public function showPdfFile($html): void
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->stream('details.pdf', [
            'Attachement'=> false
        ]);
    }

    public function generateBinaryPdf($html): void
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        $this->domPdf->output();
    }

}