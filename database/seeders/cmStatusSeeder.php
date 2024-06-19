<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Deployment\DeploymentCMStatus;

class cmStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $cmStatus = [
            'Checker' => [
                'colour' => '#0a572b',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'Done Deploy' => [
                'colour' => '#e07a1a',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'Draft' => [
                'colour' => '#00ff08',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'Reviewer' => [
                'colour' => '#3c1dd7',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'Signed' => [
                'colour' => '#ff6347',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        
            // Tambahkan lainnya sesuai kebutuhan
        ];

        foreach ($cmStatus as $cmName => $cmData) {
            DeploymentCMStatus::firstOrCreate(['cm_status_name' => $cmName], $cmData);
        }
    }
}
