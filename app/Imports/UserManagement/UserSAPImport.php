<?php

namespace App\Imports\UserManagement;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\UserManagement\UsmanSAP;

class UserSAPImport implements ToCollection, WithHeadingRow

{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        ini_set('max_execution_time', 3600);
        foreach ($rows as $row) {
            $rowArray = $row->toArray();

            $incident = UsmanSAP::where('user_pn', $rowArray['user_pn'])->first();

            if ($incident) {
                $updatedFields = $this->checkForUpdates($incident, $rowArray);
                if (!empty($updatedFields)) {
                    $incident->update($updatedFields);
                }
            } else {
                UsmanSAP::create($this->modelArray($rowArray));
            }
        }
    }

    private function modelArray(array $row)
    {

        return [
            'user_pn' => $row['user_pn'],
            'creation_date' => $this->convertExcelDate($row['creation_date']),
            'last_logon' => $this->convertExcelDate($row['last_logon']),
            'inactive_month' => $row['inactive_month'],
            'inactive_category' => $row['inactive_category'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'branch_code' => $row['branch_code'],
            'department' => $row['department'],
            'branch_level' => $row['branch_level'],
            'lock_status' => $row['lock_status'],
            'user_group' => $row['user_group'],
            'div_code' => $row['div_code'],
        ];
    }

    private function checkForUpdates(UsmanSAP $incident, array $row)
    {
        $attributes = $this->modelArray($row);
        $changes = [];

        foreach ($attributes as $key => $value) {
            if ($incident->$key != $value) {
                $changes[$key] = $value;
            }
        }

        return $changes;
    }

    private function convertExcelDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            $unixDate = ($excelDate - 25569) * 86400;
            return gmdate("Y-m-d", $unixDate);
        }

        return null;
    }
}
