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
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remaja_id');
            $table->string('perihal');
            $table->text('deskripsi');
            $table->enum('status', ['Belum dibalas', 'Sudah dibalas'])->default('Belum dibalas');
            $table->text('balasan')->nullable();
            $table->timestamps();

            $table->foreign('remaja_id')->references('id')->on('remaja')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluhan');
    }
};
