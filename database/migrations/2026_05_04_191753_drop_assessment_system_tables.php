<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'assessment_id']);
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['assessment_id']);
            $table->dropColumn('assessment_id');
        });

        Schema::dropIfExists('assessments');
        Schema::dropIfExists('assessment_types');
    }

    public function down(): void
    {
        Schema::create('assessment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_term_bound')->default(true);
            $table->timestamps();
        });

        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessment_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('term_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('title_ar')->nullable();
            $table->decimal('max_score', 6, 2)->default(100);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('assessment_id')->nullable()->after('term_id')->constrained()->nullOnDelete();
            $table->unique(['student_id', 'assessment_id']);
        });
    }
};
