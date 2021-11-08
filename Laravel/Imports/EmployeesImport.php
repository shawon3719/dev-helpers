<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToModel,WithHeadingRow,WithChunkReading
{
    use Importable;

    private $rows = 0;

    public function model(array $row)
    {
        ++$this->rows;

        return new Employee([
            'name'              => $row[0],
            'email'             => $row[1],
            'sex'               => $row[2],
            'blood_group'       => $row[3],
            'date_of_birth'     => $row[4],
            'phone'             => $row[5],
            'alt_phone'         => $row[6],
            'address'           => $row[7],
            'city'              => $row[8],
            'district'          => $row[9],
            'division'          => $row[10],
            'joining_date'      => $row[11],
            'NID'               => $row[12],
            'emergency_contact' => $row[13],
            'remarks'           => $row[14],
            'user_id'           => $row[15],
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
