<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->string('name_ar')->nullable()->after('name');
        });

        \DB::table('levels')->update([
            'name_ar' => \DB::raw("CASE
                WHEN `order` = 1 THEN 'المستوى الأول'
                WHEN `order` = 2 THEN 'المستوى الثاني'
                WHEN `order` = 3 THEN 'المستوى الثالث'
                ELSE name
            END"),
        ]);
    }

    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropColumn('name_ar');
        });
    }
};
