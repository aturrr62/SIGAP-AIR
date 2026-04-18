<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->unique()->constrained('pengaduan')->cascadeOnDelete();
            $table->foreignId('petugas_id')->constrained('petugas');
            $table->foreignId('assigned_by')->constrained('users');
            $table->dateTime('jadwal_penanganan')->nullable();
            $table->text('instruksi')->nullable();
            $table->enum('status_assignment', ['ditugaskan', 'sedang_diproses', 'selesai'])->default('ditugaskan');
            $table->text('catatan_petugas')->nullable();
            $table->string('foto_penanganan')->nullable();
            $table->timestamp('timestamp_mulai')->nullable();
            $table->timestamp('timestamp_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment');
    }
};
