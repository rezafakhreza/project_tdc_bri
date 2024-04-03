<?php

namespace App\Imports\UserManagement;

use App\Models\UserManagement\Branch;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class BranchImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return DB::transaction(function () use ($row) {

            return new Branch([
                'branch_code' => $row['kode_kerja'],
                'branc_name' => $row['nama_uker'],
                'kanwil_code' => $row['uker_induk'],
                'kanwil_name' => $row['level_uker'],

            ]);
        });
    }

    
}
