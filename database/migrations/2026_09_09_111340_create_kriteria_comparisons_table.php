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
        Schema::create('kriteria_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria1_id')->constrained('kriterias')->cascadeOnDelete();
            $table->foreignId('kriteria2_id')->constrained('kriterias')->cascadeOnDelete();
            $table->double('nilai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria_comparisons');
    }
};
