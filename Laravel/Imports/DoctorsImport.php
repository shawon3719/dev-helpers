<?php

namespace App\Imports;

use App\Models\Doctor;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DoctorsImport implements ToModel,WithHeadingRow,WithChunkReading
{
    use Importable;

    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;

        return new Doctor([
            'name'              => $row[0],
            'last_degree'       => $row[1],
            'email'             => $row[2],
            'sex'               => $row[3],
            'blood_group'       => $row[4],
            'date_of_birth'     => $row[5],
            'phone'             => $row[6],
            'alt_phone'         => $row[7],
            'address'           => $row[8],
            'city'              => $row[9],
            'district'          => $row[10],
            'division'          => $row[11],
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
