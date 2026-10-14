<?php

namespace App\Services;

use App\Exports\ArrayExport;
use App\Services\Contracts\ExcelServiceInterface;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelService implements ExcelServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function downloadArray(array $data, array $headings = [], string $fileName = 'export.xlsx'): BinaryFileResponse
    {
        return Excel::download(new ArrayExport($data, $headings), $fileName);
    }

    /**
     * {@inheritDoc}
     */
    public function downloadCustom(object $exportInstance, string $fileName = 'export.xlsx'): BinaryFileResponse
    {
        return Excel::download($exportInstance, $fileName);
    }
}
