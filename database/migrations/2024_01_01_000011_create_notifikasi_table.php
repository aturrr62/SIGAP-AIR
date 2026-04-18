<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pengaduan_id')->nullable()->constrained('pengaduan')->nullOnDelete();
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['status_berubah', 'verifikasi', 'assignment', 'overdue', 'rating']);
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_read'], 'idx_user_unread');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
