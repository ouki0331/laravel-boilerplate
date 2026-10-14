<?php

namespace App\Services\Contracts;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface ExcelServiceInterface
{
    /**
     * Export an array of data with headings directly as a downloaded Excel file.
     *
     * @param  array<int, array<string, mixed>>  $data
     * @param  array<string>  $headings
     */
    public function downloadArray(array $data, array $headings = [], string $fileName = 'export.xlsx'): BinaryFileResponse;

    /**
     * Export using a dedicated export class.
     */
    public function downloadCustom(object $exportInstance, string $fileName = 'export.xlsx'): BinaryFileResponse;
}
