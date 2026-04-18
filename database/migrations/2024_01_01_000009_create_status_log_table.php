<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained('pengaduan')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('status_lama')->nullable();
            $table->string('status_baru');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_log');
    }
};
