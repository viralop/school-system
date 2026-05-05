<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Level::insertOrIgnore([
            ['name' => 'Level 1', 'name_ar' => 'المستوى الأول', 'order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Level 2', 'name_ar' => 'المستوى الثاني', 'order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Level 3', 'name_ar' => 'المستوى الثالث', 'order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $level3 = Level::where('name', 'Level 3')->first();

        if ($level3 && $level3->subjects()->count() === 0) {
            $level3->subjects()->createMany([
                ['name_en' => 'Arabic', 'name_ar' => 'اللغة العربية', 'default_max_degree' => 40],
                ['name_en' => 'English', 'name_ar' => 'اللغة الإنجليزية', 'default_max_degree' => 40],
                ['name_en' => 'Mathematics', 'name_ar' => 'الرياضيات', 'default_max_degree' => 40],
                ['name_en' => 'Islamic Education', 'name_ar' => 'التربية الإسلامية', 'default_max_degree' => 40],
                ['name_en' => 'Natural Sciences', 'name_ar' => 'العلوم الطبيعية', 'default_max_degree' => 30],
                ['name_en' => 'Geography', 'name_ar' => 'الجغرافيا', 'default_max_degree' => 30],
                ['name_en' => 'History', 'name_ar' => 'التاريخ', 'default_max_degree' => 30],
                ['name_en' => 'Technical Education', 'name_ar' => 'التربية التقنية', 'default_max_degree' => 20],
                ['name_en' => 'ICT', 'name_ar' => 'تكنولوجيا المعلومات والاتصالات', 'default_max_degree' => 20],
            ]);
        }
    }
}
