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
        Schema::create('personnel_assets_pending', function (Blueprint $table) {
            $table->id();
            $table->date('assignment_date')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->integer('asset_id');
            $table->integer('assigner_id');
            $table->integer('receiver_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_assets_pending');
    }
};
