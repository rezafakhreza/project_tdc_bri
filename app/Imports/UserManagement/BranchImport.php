<?php

namespace App\Imports\UserManagement;

use App\Models\UserManagement\Branch;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class BranchImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $rowArray = $row->toArray();

            $branch = Branch::where('branch_code', $rowArray['kode_uker'])->first();

            if ($branch) {
                $updatedFields = $this->checkForUpdates($branch, $rowArray);
                if (!empty($updatedFields)) {
                    $branch->update($updatedFields);
                }
            } else {
                Branch::create($this->modelArray($rowArray));
            }
        }
    }

    private function modelArray(array $row)
    {
        return [
            'branch_code' => $row['kode_uker'],
            'branch_name' => $row['nama_uker'],
            'kanwil_code' => $row['uker_induk_kanwil'],
            'kanwil_name' => $row['level_uker'],
        ];
    }

    private function checkForUpdates(Branch $branch, array $row)
    {
        $attributes = $this->modelArray($row);
        $changes = [];

        foreach ($attributes as $key => $value) {
            if ($branch->$key != $value) {
                $changes[$key] = $value;
            }
        }

        return $changes;
    }

    // private function convertExcelDate($excelDate)
    // {
    //     if (is_numeric($excelDate)) {
    //         $unixDate = ($excelDate - 25569) * 86400;
    //         return gmdate("Y-m-d", $unixDate);
    //     }

    //     return null;
    // }

    /**
     * @return int
     */
    // heading
    // public function headingRow(): int
    // {
    //     return 3;
    // }
}
