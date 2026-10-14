<?php

namespace App\Services;

use App\Services\Contracts\PdfServiceInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class PdfService implements PdfServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function renderToBinary(string $view, array $data = [], array $options = []): string
    {
        $pdf = Pdf::loadView($view, $data);

        if (! empty($options['paper']) && ! empty($options['orientation'])) {
            $pdf->setPaper($options['paper'], $options['orientation']);
        }

        return $pdf->output();
    }

    /**
     * {@inheritDoc}
     */
    public function download(string $view, array $data = [], string $filename = 'document.pdf'): Response
    {
        $pdf = Pdf::loadView($view, $data);

        return $pdf->download($filename);
    }

    /**
     * {@inheritDoc}
     */
    public function saveToFile(string $view, array $data, string $savePath): bool
    {
        try {
            $pdf = Pdf::loadView($view, $data);
            $directory = dirname($savePath);

            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $pdf->save($savePath);

            return true;
        } catch (Throwable $e) {
            Log::error('PdfService saveToFile failed: '.$e->getMessage(), [
                'savePath' => $savePath,
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }
}
