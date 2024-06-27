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
        Schema::create('pengukuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remaja_id')->constrained('remaja')->onDelete('cascade'); 
            $table->date('tanggal_pengukuran');
            $table->float('bb');
            $table->float('tb');
            $table->float('lila');
            $table->string('tensi');
            $table->string('status_gizi');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengukurans');
    }
};
