<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 100);
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('harga')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_layanan');
    }
};

