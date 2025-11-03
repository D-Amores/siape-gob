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
        Schema::create('personnel_assets', function (Blueprint $table) {
            $table->id();
            $table->date('assignment_date')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->string('path_acceptance_doc');
            $table->string('path_respaldo_acceptance')->nullable();
            $table->enum('status', ['assigned', 'unassigned'])->default('assigned');
            $table->date('unassignment_date')->nullable();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('restrict');
            $table->foreignId('assigner_id')->constrained('personnel', 'id')->onDelete('restrict');
            $table->foreignId('receiver_id')->constrained('personnel', 'id')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_assets');
    }
};
