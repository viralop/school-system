<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_exam_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->decimal('max_degree', 6, 2)->default(100);
            $table->timestamps();

            $table->unique(['monthly_exam_id', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_exam_subjects');
    }
};
