<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Deployment\DeploymentServerType;

class ServerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $serverTypes = [
            'BW/4HANA' => [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'HANA IEM' => [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'S/4HANA' => [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Tambahkan module lainnya sesuai kebutuhan
        ];

        foreach ($serverTypes as $serverTypeName => $serverTypeData) {
            DeploymentServerType::firstOrCreate(['name' => $serverTypeName], $serverTypeData);
        }
    }
}
