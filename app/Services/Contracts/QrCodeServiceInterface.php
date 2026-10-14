<?php

namespace App\Services\Contracts;

interface QrCodeServiceInterface
{
    /**
     * Generate an SVG QR code string.
     */
    public function generateSvg(string $data, int $size = 200, int $margin = 0): string;

    /**
     * Generate a base64 encoded SVG image data URI.
     */
    public function generateBase64DataUri(string $data, int $size = 200): string;

    /**
     * Save an SVG QR code to a file path.
     */
    public function saveToFile(string $data, string $filePath, int $size = 200): bool;
}
