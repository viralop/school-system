<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Level::insert([
            ['name' => 'Level 1', 'name_ar' => 'المستوى الأول', 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Level 2', 'name_ar' => 'المستوى الثاني', 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Level 3', 'name_ar' => 'المستوى الثالث', 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
