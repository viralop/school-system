<?php

namespace App\Console\Commands;

use App\Models\Level;
use App\Models\Student;
use Illuminate\Console\Command;

class SeedStudents extends Command
{
    protected $signature = 'seed:students {--count=50 : Students per level}';

    protected $description = 'Seed students across all levels, divided equally among sections';

    public function handle(): int
    {
        $perLevel = (int) $this->option('count');
        $levels = Level::with('sections')->orderBy('order')->get();

        if ($levels->isEmpty()) {
            $this->error('No levels found. Run the DatabaseSeeder first.');
            return 1;
        }

        $total = 0;

        foreach ($levels as $level) {
            $sections = $level->sections;
            $sectionCount = $sections->count();

            $this->info("Level: {$level->name} ({$sectionCount} sections)");

            for ($i = 0; $i < $perLevel; $i++) {
                $studentNumber = $level->nextStudentNumber();

                $sectionId = null;
                if ($sectionCount > 0) {
                    $sectionId = $sections[$i % $sectionCount]->id;
                }

                Student::create([
                    'student_number' => $studentNumber,
                    'name' => "Student {$studentNumber}",
                    'parent_phone' => '05' . rand(10000000, 99999999),
                    'level_id' => $level->id,
                    'section_id' => $sectionId,
                    'status' => 'active',
                ]);

                $total++;
            }

            $perSection = $sectionCount > 0 ? intdiv($perLevel, $sectionCount) : 0;
            $remainder = $sectionCount > 0 ? $perLevel % $sectionCount : 0;
            $this->line("  Created {$perLevel} students");
            if ($sectionCount > 0) {
                for ($s = 0; $s < $sectionCount; $s++) {
                    $count = $perSection + ($s < $remainder ? 1 : 0);
                    $this->line("    Section {$sections[$s]->name}: {$count} students");
                }
            }
        }

        $this->info("Done! Created {$total} students total.");

        return 0;
    }
}
