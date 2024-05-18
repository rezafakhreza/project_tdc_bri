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
        Schema::create('deployment_has_server_type', function (Blueprint $table) {
            $table->string('deployment_id');
            $table->foreign('deployment_id')->references('id')->on('deployments')->onDelete('cascade');
            $table->unsignedBigInteger('server_type_id');
            $table->foreign('server_type_id')->references('id')->on('deployment_server_types')->onDelete('cascade');
            $table->timestamps();
            $table->primary(['deployment_id', 'server_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deployment_has_server_type');
    }
};
