<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArrayExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    /**
     * @param  array<int, array<string, mixed>>  $data
     * @param  array<string>  $headings
     */
    public function __construct(
        protected array $data,
        protected array $headings = []
    ) {}

    public function collection(): Collection
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return $this->headings;
    }
}
