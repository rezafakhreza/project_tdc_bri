<?php

namespace App\Imports\Brisol;

use App\Models\Brisol\FoundationFAM;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;

class FoundationFAMImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $rows)
    {
        ini_set('max_execution_time', 3600);
        foreach ($rows as $row) {
            $rowArray = $row->toArray();

            $fam = FoundationFAM::where('buss_service_fam', $rowArray['business_service_fam'])->first();

            if ($fam) {
                $updatedFields = $this->checkForUpdates($fam, $rowArray);
                if (!empty($updatedFields)) {
                    $fam->update($updatedFields);
                }
            } else {
                FoundationFAM::create($this->modelArray($rowArray));
            }
        }
    }

    private function modelArray(array $row)
    {
        return [
            'buss_service_fam' => $row['business_service_fam'],
            'prd_tier1' => $row['product_categorization_tier_1'],
            'prd_tier2' => $row['product_categorization_tier_2'],
            'prd_tier3' => $row['product_categorization_tier_3'],
            'op_tier1' => $row['operational_categorization_tier_1'],
            'op_tier2' => $row['operational_categorization_tier_2'],
            'op_tier3' => $row['operational_categorization_tier_3'],
            'resolution_category' => $row['resolution_categorization']
        ];
    }

    private function checkForUpdates(FoundationFAM $fam, array $row)
    {
        $attributes = $this->modelArray($row);
        $changes = [];

        foreach ($attributes as $key => $value) {
            if ($fam->$key != $value) {
                $changes[$key] = $value;
            }
        }

        return $changes;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
