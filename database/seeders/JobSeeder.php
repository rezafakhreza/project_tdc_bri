<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\BackgroundJobsMonitoring\Process;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $now = Carbon::now();

        $Jobs = 
        ['INBOUND - A001 (Indonesia) - FPSL' => ['type' => 'Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'INBOUND - A002 (Singapore)' => ['type' => 'Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'INBOUND - A003 (Timor Leste)' => ['type' => 'Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'INBOUND - A004 (Newyork)' => ['type' => 'Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'INBOUND - A005 (Cayman Island)' => ['type' => 'Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'INBOUND - A006 (Taiwan)' => ['type' => 'Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'OUTBOUND - Master Data' => ['type' => 'Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'OUTBOUND - Transaction Data' => ['type' => 'Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'REVAL UKDN' => ['type' => 'Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'REVAL UKLN & RECLASS CLOSING' => ['type' => 'Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        
        'BRISMART' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'WLA' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'DASHBOARD FAMP' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'MASTER DATA BANK RTGS' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'BRIHC INTERFACE - PERSONNEL DATA' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'ZRFIAP_BRIHC_CC_UPDATE' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'BRIHC - WAGETYPE DAILY' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'BRIFX INTERFACE' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'SIPOBRI INBOUND' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'BACKUP DB12' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'TRANSACTION SAP BW (FIGL_12)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'MASTER DATA BGP SAP BW (MD_ALL)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'BRIHC PAYROLL ($) - MONTHLY (EVERY 21nd - LAST DATE OF THE MONTHS)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'BRIHC PAYSLIP ($) - MONTHLY (EVERY 21nd - LAST DATE OF THE MONTHS)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'VALUASI ASET SAP FAM - MONTHLY (EVERY 25nd)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'DEPRESIASI ASET SAP FAM - MONTHLY (EVERY 25nd)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'OUTBOUND GLMAP OCOA BRINETS - MONTHLY (EVERY 1st)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'OUTBOUND Report Antasena Asset FAM - MONTHLY (EVERY 1st)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'OUTBOUND CONSOLIDATION - MONTHLY (EVERY 1st)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'ITAM (FAM) ZZEKPO - MONTHLY (EVERY 1st)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        'CHANGE_PERIOD - MONTHLY (EVERY LAST DATE OF THE MONTHS)' => ['type' => 'Non-Product','is_active' => 1, 'created_at' => $now, 'updated_at' => $now,],
        ];

        foreach ($Jobs as $JobName => $JobData) {
            Process::firstOrCreate(['name' => $JobName], $JobData);
        }
    }
}
