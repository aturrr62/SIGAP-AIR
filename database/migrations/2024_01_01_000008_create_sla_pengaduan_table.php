<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->unique()->constrained('pengaduan')->cascadeOnDelete();
            $table->dateTime('batas_waktu');
            $table->enum('status_sla', ['berjalan', 'terpenuhi', 'overdue'])->default('berjalan');
            $table->boolean('is_flagged')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_pengaduan');
    }
};
