<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->string('exam_type')->nullable()->after('term_id');
            $table->unsignedBigInteger('exam_id')->nullable()->after('exam_type');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->unique(['student_id', 'subject_id', 'exam_type', 'exam_id'], 'grades_unique_exam');
        });
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropUnique('grades_unique_exam');
            $table->dropColumn(['exam_type', 'exam_id']);
        });
    }
};
