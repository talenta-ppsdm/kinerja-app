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
        Schema::create('skp_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->string('predikat_tw1');
            $table->string('predikat_tw2');
            $table->string('predikat_tw3');
            $table->string('predikat_tw4');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skp_evaluasi');
    }
};
