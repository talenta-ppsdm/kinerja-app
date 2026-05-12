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
        Schema::table('skp_penyusunan', function (Blueprint $table) {
            $table->unsignedBigInteger('skp_id')->nullable()->after('status_skp');
            $table->foreign('skp_id')->references('id')->on('skp')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skp_penyusunan', function (Blueprint $table) {
            $table->dropForeign(['skp_id']);
            $table->dropColumn('skp_id');
        });
    }
};
