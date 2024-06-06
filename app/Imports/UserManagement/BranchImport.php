<?php

namespace App\Imports\UserManagement;

use App\Models\UserManagement\Branch;
use App\Models\UserManagement\Incident;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

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
            'level_uker' => $row['level_uker'],
            'uker_induk_wilayah_code' => $row['uker_induk_kanwil'],
            'kanwil_name' => $row['nama_kanwil'],
            'uker_induk_kc' => $row['uker_induk_kc'],
            'sbo' => $row['sbo'],
            'is_active' => Incident::where('branch_code', $row['kode_uker'])->exists() ? true : false
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

        if ($branch->is_active!= $attributes['is_active']) {
            $changes['is_active'] = $attributes['is_active'];
        }

        return $changes;
    }

    
    /**
     * @return int
     */
    // heading
    // public function headingRow(): int
    // {
    //     return 3;
    // }
}
