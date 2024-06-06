<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usman_branch', function (Blueprint $table) {
            $table->string('branch_code')->primary();
            $table->string('branch_name');
            $table->enum('level_uker', ['AIW', 'BRI UNIT', 'Campus', 'Kanpus', 'KC', 'KCP', 'KK', 'Regional Office']);
            $table->string('uker_induk_wilayah_code');
            $table->string('kanwil_name');
            $table->string('uker_induk_kc');
            $table->enum('sbo', ['NON SBO', 'SBO']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usman_branch');
    }
};
