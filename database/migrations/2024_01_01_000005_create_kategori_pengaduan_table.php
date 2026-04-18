<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('kode_kategori')->unique();
            $table->text('deskripsi')->nullable();
            $table->integer('sla_jam')->default(24);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_pengaduan');
    }
};
