<?php

namespace Modules\DiagnosticCentre\Imports;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\DiagnosticCentre\Entities\Category;

class PathologyCategoriesImport implements ToModel,WithHeadingRow,WithChunkReading
{
    use Importable;

    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;

        return new Category([
            'name'              => $row[0],
            'type'              => $row[1],
            'description'       => $row[2],
            'parent_category'   => $row[3],
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
