<?php

namespace App\Console\Commands;

use App\Models\Level;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Console\Command;

class SeedSubjects extends Command
{
    protected $signature = 'seed:subjects {--count=11}';

    protected $description = 'Seed subjects across levels, distributed equally, assigned to teachers';

    public function handle(): int
    {
        $existing = Subject::count();
        $target = (int) $this->option('count');
        $needed = max(0, $target - $existing);

        if ($needed === 0) {
            $this->info("Already have {$existing} subjects. Nothing to do.");
            return 0;
        }

        $levels = Level::orderBy('order')->get();
        $teachers = User::where('role', 'teacher')->where('status', 'active')->get();

        $subjectNames = [
            'Mathematics', 'Physics', 'Chemistry', 'Biology', 'English',
            'Arabic', 'Islamic Studies', 'History', 'Geography', 'Computer Science',
            'Art', 'Physical Education', 'Social Studies', 'Quran', 'Science',
        ];

        $existingNames = Subject::pluck('name')->toArray();
        $available = array_values(array_diff($subjectNames, $existingNames));

        $perLevel = intdiv($needed, $levels->count());
        $remainder = $needed % $levels->count();

        $created = 0;
        $nameIndex = 0;

        foreach ($levels as $i => $level) {
            $count = $perLevel + ($i < $remainder ? 1 : 0);

            for ($j = 0; $j < $count; $j++) {
                if ($nameIndex >= count($available)) {
                    $nameIndex = 0;
                }

                $name = $available[$nameIndex];
                $nameIndex++;

                $teacher = $teachers->isNotEmpty()
                    ? $teachers[($created) % $teachers->count()]
                    : null;

                Subject::create([
                    'name' => $name,
                    'max_score' => 100,
                    'level_id' => $level->id,
                    'teacher_id' => $teacher?->id,
                ]);

                $teacherName = $teacher ? $teacher->name : 'No teacher';
                $this->line("  {$level->name} | {$name} | Teacher: {$teacherName}");
                $created++;
            }
        }

        $this->info("Created {$created} subject(s). Total: " . Subject::count());

        return 0;
    }
}
