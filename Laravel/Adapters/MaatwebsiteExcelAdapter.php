<?php

namespace App\Adapters;

use App\Interfaces\ExcelExportInterface;
use Maatwebsite\Excel\Facades\Excel;

class MaatwebsiteExcelAdapter implements ExcelExportInterface
{
    public function download(string $exportClassName, array $bladeData, string $fileName='', string $sheetName='')
    {
        $exportClassNameWithPath =  'App\Exports\\'.$exportClassName;

        $fileName = $fileName === '' ? 'excel_file' : $fileName;

        $excelFileName = $fileName.'_'.time().'.xlsx';

        return Excel::download(new $exportClassNameWithPath($bladeData, $sheetName), $excelFileName);
    }
}
