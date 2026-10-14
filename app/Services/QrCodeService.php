<?php

namespace App\Services;

use App\Services\Contracts\QrCodeServiceInterface;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Throwable;

class QrCodeService implements QrCodeServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function generateSvg(string $data, int $size = 200, int $margin = 0): string
    {
        return (string) QrCode::format('svg')
            ->size($size)
            ->margin($margin)
            ->generate($data);
    }

    /**
     * {@inheritDoc}
     */
    public function generateBase64DataUri(string $data, int $size = 200): string
    {
        $svg = $this->generateSvg($data, $size);

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    /**
     * {@inheritDoc}
     */
    public function saveToFile(string $data, string $filePath, int $size = 200): bool
    {
        try {
            $directory = dirname($filePath);
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $svg = $this->generateSvg($data, $size);
            file_put_contents($filePath, $svg);

            return true;
        } catch (Throwable $e) {
            Log::error('QrCodeService saveToFile failed: '.$e->getMessage(), [
                'filePath' => $filePath,
                'trace' => $e->getTraceAsString(),
            ]);

            return false;
        }
    }
}
