<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SeedTeachers extends Command
{
    protected $signature = 'seed:teachers {--count=13}';

    protected $description = 'Seed teacher accounts';

    public function handle(): int
    {
        $existing = User::where('role', 'teacher')->count();
        $target = (int) $this->option('count');
        $needed = max(0, $target - $existing);

        if ($needed === 0) {
            $this->info("Already have {$existing} teachers. Nothing to do.");
            return 0;
        }

        for ($i = 1; $i <= $needed; $i++) {
            $num = $existing + $i;
            User::create([
                'name' => "Teacher {$num}",
                'email' => "teacher{$num}@alwefaq.com",
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'status' => 'active',
            ]);
        }

        $this->info("Created {$needed} teacher(s). Total: " . User::where('role', 'teacher')->count());

        return 0;
    }
}
