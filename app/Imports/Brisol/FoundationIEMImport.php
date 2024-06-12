<?php

namespace App\Imports\Brisol;

use App\Models\Brisol\FoundationIEM;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;

class FoundationIEMImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public function collection(Collection $rows)
    {
        ini_set('max_execution_time', 3600);
        foreach ($rows as $row) {
            $rowArray = $row->toArray();

            $iem = FoundationIEM::where('buss_service_iem', $rowArray['business_service_iem'])->first();

            if ($iem) {
                $updatedFields = $this->checkForUpdates($iem, $rowArray);
                if (!empty($updatedFields)) {
                    $iem->update($updatedFields);
                }
            } else {
                FoundationIEM::create($this->modelArray($rowArray));
            }
        }
    }

    private function modelArray(array $row)
    {
        return [
            'buss_service_iem' => $row['business_service_iem'],
            'prd_tier1' => $row['product_categorization_tier_1'],
            'prd_tier2' => $row['product_categorization_tier_2'],
            'prd_tier3' => $row['product_categorization_tier_3'],
            'op_tier1' => $row['operational_categorization_tier_1'],
            'op_tier2' => $row['operational_categorization_tier_2'],
            'op_tier3' => $row['operational_categorization_tier_3'],
            'resolution_category' => $row['resolution_categorization']
        ];
    }

    private function checkForUpdates(FoundationIEM $iem, array $row)
    {
        $attributes = $this->modelArray($row);
        $changes = [];

        foreach ($attributes as $key => $value) {
            if ($iem->$key != $value) {
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
