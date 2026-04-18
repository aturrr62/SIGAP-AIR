<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('zona_id')->constrained('zona_wilayah')->cascadeOnDelete();
            $table->string('nip')->unique()->nullable();
            $table->enum('status_tersedia', ['tersedia', 'sibuk', 'tidak_aktif'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
