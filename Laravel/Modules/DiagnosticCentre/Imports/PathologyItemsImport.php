<?php

namespace Modules\DiagnosticCentre\Imports;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\DiagnosticCentre\Entities\Item;

class PathologyItemsImport implements ToModel,WithHeadingRow,WithChunkReading
{
    use Importable;

    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;

        return new Item([
            'name'          => $row[0],
            'price'         => $row[1],
            'offer_price'   => $row[2],
            'exp_date'      => $row[3],
            'description'   => $row[4],
            'category'      => $row[5],
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
