<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_ar')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_term_bound')->default(true);
            $table->timestamps();
        });

        DB::table('assessment_types')->insert([
            ['name' => 'First Term Exam', 'name_ar' => 'اختبار الفصل الأول', 'slug' => 'first_term_exam', 'is_term_bound' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Monthly Exam', 'name_ar' => 'اختبار شهري', 'slug' => 'monthly_exam', 'is_term_bound' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Final Term Exam', 'name_ar' => 'اختبار الفصل النهائي', 'slug' => 'final_term_exam', 'is_term_bound' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_types');
    }
};
