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
        Schema::create('maintenance_report_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_report_id')->constrained('maintenance_reports')->onDelete('restrict');
            $table->foreignId('personnel_id')->constrained('personnel')->onDelete('restrict');
            $table->string('action', 100); // Ej: "Aprobado", "Finalizado", "Propuesta de baja"
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_report_logs');
    }
};
