<?php

namespace App\Services\Contracts;

use Illuminate\Http\Response;

interface PdfServiceInterface
{
    /**
     * Render a view template to raw PDF binary.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $options
     */
    public function renderToBinary(string $view, array $data = [], array $options = []): string;

    /**
     * Download a rendered PDF directly as an HTTP response.
     *
     * @param  array<string, mixed>  $data
     */
    public function download(string $view, array $data = [], string $filename = 'document.pdf'): Response;

    /**
     * Save PDF to local file storage.
     *
     * @param  array<string, mixed>  $data
     */
    public function saveToFile(string $view, array $data, string $savePath): bool;
}
