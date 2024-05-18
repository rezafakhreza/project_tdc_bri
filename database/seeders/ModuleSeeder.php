<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Deployment\DeploymentModule;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $modules = [
            'FAM' => [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'IEM' => [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'BGP' => [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'Consol' => [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'FPSL' => [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'PaPM' => [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            'S/4GL' => [
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Tambahkan module lainnya sesuai kebutuhan
        ];

        foreach ($modules as $moduleName => $moduleData) {
            DeploymentModule::firstOrCreate(['name' => $moduleName], $moduleData);
        }
    }
}
