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
        Schema::create('deployments', function (Blueprint $table) {
            $table->string('id',255)->primary();
            $table->string('title', 200);
            $table->date('deploy_date');
            $table->enum('document_status', ['Done', 'Not Done', 'In Progress']);
            $table->text('document_description');
            $table->enum('cm_status', ['Draft', 'Reviewer', 'Checker', 'Signer', 'Done deploy']);
            $table->text('cm_description');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
