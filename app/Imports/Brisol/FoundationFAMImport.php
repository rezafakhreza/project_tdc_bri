<?php

namespace App\Imports\Brisol;

use Illuminate\Support\Collection;
use App\Models\Brisol\FoundationFAM;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FoundationFAMImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        ini_set('max_execution_time', 3600);
        return DB::transaction(function () use ($row) {

            return new FoundationFAM([
                'buss_service_fam' => $row['business_service'],
                'prd_tier1' => $row['product_categorization_tier_1'],
                'prd_tier2' => $row['product_categorization_tier_2'],
                'prd_tier3' => $row['product_categorization_tier_3'],
                'op_tier1' => $row['operational_categorization_tier_1'],
                'op_tier2' => $row['operational_categorization_tier_2'],
                'op_tier3' => $row['operational_categorization_tier_3'],
                'resolution_category' => $row['resolution_categorization']
            ]);
        });
    }

    public function headingRow(): int
    {
        return 1;
    }
}
