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
        Schema::create('brisol_foundation', function (Blueprint $table) {
            $table->id();
            $table->string('buss_service');
            $table->string('prd_tier1');
            $table->string('prd_tier2');
            $table->string('prd_tier3');
            $table->string('op_tier1');
            $table->string('op_tier2');
            $table->string('op_tier3');
            $table->string('resolution_category');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brisol_foundation');
    }
};
