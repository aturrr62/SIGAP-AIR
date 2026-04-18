<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rating_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->unique()->constrained('pengaduan')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->tinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating_feedback');
    }
};
