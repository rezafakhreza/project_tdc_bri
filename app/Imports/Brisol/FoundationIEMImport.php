<?php

namespace App\Imports\Brisol;

use App\Models\Brisol\FoundationIEM;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FoundationIEMImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        ini_set('max_execution_time', 3600);
        
        return DB::transaction(function () use ($row) {
            // Definisikan kolom-kolom unik untuk pengecekan duplikasi
            $uniqueFields = [
                'buss_service_iem' => $row['business_service_iem'],
                'prd_tier1' => $row['product_categorization_tier_1'],
                'prd_tier2' => $row['product_categorization_tier_2'],
                'prd_tier3' => $row['product_categorization_tier_3'],
                'op_tier1' => $row['operational_categorization_tier_1'],
                'op_tier2' => $row['operational_categorization_tier_2'],
                'op_tier3' => $row['operational_categorization_tier_3'],
                'resolution_category' => $row['resolution_categorization']
            ];
            
            // Cek apakah data dengan kolom-kolom unik tersebut sudah ada di database
            $existingRecord = FoundationIEM::where($uniqueFields)->first();
            
            if ($existingRecord) {
                // Jika ditemukan duplikasi, lewati penyisipan data baru
                return null;
            }
            
            // Jika tidak ditemukan duplikasi, buat data baru
            return new FoundationIEM($uniqueFields);
        });
    }

    public function headingRow(): int
    {
        return 1;
    }
}
