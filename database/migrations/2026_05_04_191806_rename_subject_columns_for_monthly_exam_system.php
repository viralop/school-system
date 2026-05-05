<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->renameColumn('name', 'name_en');
            $table->renameColumn('max_score', 'default_max_degree');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->renameColumn('name_en', 'name');
            $table->renameColumn('default_max_degree', 'max_score');
        });
    }
};
