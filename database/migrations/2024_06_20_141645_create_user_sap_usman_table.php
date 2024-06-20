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
        Schema::create('user_sap_usman', function (Blueprint $table) {
            $table->id();
            $table->string('user_pn');
            $table->date('creation_date');
            $table->date('last_logon');
            $table->string('inactive_month');
            $table->string('inactive_category');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('branch_code');
            $table->string('department');
            $table->string('branch_level');
            $table->string('lock_status');
            $table->string('user_group');
            $table->string('div_code');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sap_usman');
    }
};
