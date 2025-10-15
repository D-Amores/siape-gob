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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_number')->unique();
            $table->string('model');
            $table->string('serial_number')->unique()->nullable();
            $table->string('cpu')->nullable()->comment('Ej: Intel i5-11400H');
            $table->string('speed')->nullable()->comment('Ej: 2.7GHz');
            $table->string('memory')->nullable()->comment('Ej: 8GB DDR4');
            $table->string('storage')->nullable()->comment('Ej: 512GB SSD');
            $table->text('description')->nullable();
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->boolean('is_active')->default(true)->comment('Activo o inactivo');
            $table->timestamps();

            $table->index('brand_id');
            $table->index('category_id');
            $table->index('model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
