<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $firstTermType = DB::table('assessment_types')->where('slug', 'first_term_exam')->first();
        $finalTermType = DB::table('assessment_types')->where('slug', 'final_term_exam')->first();

        if ($firstTermType) {
            $existing = DB::table('terms')
                ->select('id', 'name', 'name_ar', 'level_id')
                ->get();

            foreach ($existing as $term) {
                $subjects = DB::table('subjects')->where('level_id', $term->level_id)->get();
                foreach ($subjects as $subject) {
                    $typeId = str_contains(strtolower($term->name), 'final') ? $finalTermType->id : $firstTermType->id;
                    DB::table('assessments')->insert([
                        'subject_id' => $subject->id,
                        'assessment_type_id' => $typeId,
                        'term_id' => $term->id,
                        'title' => $term->name . ' - ' . $subject->name,
                        'title_ar' => ($term->name_ar ?? $term->name) . ' - ' . ($subject->name_ar ?? $subject->name),
                        'max_score' => $subject->max_score,
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('assessment_id')->nullable()->after('term_id')->constrained()->nullOnDelete();
        });

        $assessments = DB::table('assessments')->get();
        foreach ($assessments as $assessment) {
            DB::table('grades')
                ->where('subject_id', $assessment->subject_id)
                ->where('term_id', $assessment->term_id)
                ->update(['assessment_id' => $assessment->id]);
        }
    }

    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['assessment_id']);
            $table->dropColumn('assessment_id');
        });
    }
};
