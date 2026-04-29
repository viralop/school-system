<?php

namespace App\Console\Commands;

use App\Models\Grade;
use App\Models\Level;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedGrades extends Command
{
    protected $signature = 'seed:grades';

    protected $description = 'Seed grades for all students across all subjects and terms (pending for supervisor approval)';

    public function handle(): int
    {
        $teacher = User::where('role', 'teacher')->where('status', 'active')->first();

        if (!$teacher) {
            $this->error('No active teacher found. Create teachers first.');
            return 1;
        }

        $levels = Level::with(['subjects', 'terms', 'students'])->orderBy('order')->get();
        $created = 0;
        $skipped = 0;

        foreach ($levels as $level) {
            if ($level->subjects->isEmpty() || $level->terms->isEmpty() || $level->students->isEmpty()) {
                $this->warn("Skipping {$level->name}: missing subjects, terms, or students.");
                continue;
            }

            foreach ($level->subjects as $subject) {
                $subjectTeacher = $subject->teacher_id ? User::find($subject->teacher_id) : $teacher;
                $enteredBy = $subjectTeacher ?? $teacher;

                foreach ($level->terms as $term) {
                    foreach ($level->students as $student) {
                        $exists = Grade::where('student_id', $student->id)
                            ->where('subject_id', $subject->id)
                            ->where('term_id', $term->id)
                            ->exists();

                        if ($exists) {
                            $skipped++;
                            continue;
                        }

                        $maxScore = (float) $subject->max_score;
                        $minPass = $maxScore * 0.5;

                        if (rand(1, 100) <= 80) {
                            $score = round($minPass + (mt_rand(0, 10000) / 10000) * ($maxScore - $minPass), 2);
                        } else {
                            $score = round(mt_rand(0, 10000) / 10000 * $minPass, 2);
                        }

                        $score = min($score, $maxScore);

                        Grade::create([
                            'student_id' => $student->id,
                            'subject_id' => $subject->id,
                            'term_id' => $term->id,
                            'score' => $score,
                            'status' => 'pending',
                            'entered_by' => $enteredBy->id,
                        ]);

                        $created++;
                    }
                }
            }

            $this->info("{$level->name}: done");
        }

        $this->info("Created {$created} grades, skipped {$skipped} existing. All are pending supervisor approval.");

        return 0;
    }
}
