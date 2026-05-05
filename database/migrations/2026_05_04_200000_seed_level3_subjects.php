<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now()->toDateTimeString();
        DB::table('subjects')->insert([
            ['name_en' => 'Arabic', 'name_ar' => 'اللغة العربية', 'default_max_degree' => 40, 'level_id' => 3, 'teacher_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'English', 'name_ar' => 'اللغة الإنجليزية', 'default_max_degree' => 40, 'level_id' => 3, 'teacher_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'Mathematics', 'name_ar' => 'الرياضيات', 'default_max_degree' => 40, 'level_id' => 3, 'teacher_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'Islamic Education', 'name_ar' => 'التربية الإسلامية', 'default_max_degree' => 40, 'level_id' => 3, 'teacher_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'Natural Sciences', 'name_ar' => 'العلوم الطبيعية', 'default_max_degree' => 30, 'level_id' => 3, 'teacher_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'Geography', 'name_ar' => 'الجغرافيا', 'default_max_degree' => 30, 'level_id' => 3, 'teacher_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'History', 'name_ar' => 'التاريخ', 'default_max_degree' => 30, 'level_id' => 3, 'teacher_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'Technical Education', 'name_ar' => 'التربية التقنية', 'default_max_degree' => 20, 'level_id' => 3, 'teacher_id' => null, 'created_at' => $now, 'updated_at' => $now],
            ['name_en' => 'ICT', 'name_ar' => 'تكنولوجيا المعلومات والاتصالات', 'default_max_degree' => 20, 'level_id' => 3, 'teacher_id' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        DB::table('subjects')->where('level_id', 3)->delete();
    }
};
