<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_invites', function (Blueprint $table) {
            $table->dropIndex('teacher_invites_email_status_index');
            $table->dropUnique('teacher_invites_email_unique');
        });

        Schema::table('teacher_invites', function (Blueprint $table) {
            $table->string('teacher_id')->nullable()->unique()->after('id');
        });

        DB::table('teacher_invites')->update(['teacher_id' => DB::raw("upper(hex(randomblob(4)))")]);

        Schema::table('teacher_invites', function (Blueprint $table) {
            $table->dropColumn('email');
        });

        Schema::table('teacher_invites', function (Blueprint $table) {
            $table->index(['teacher_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('teacher_invites', function (Blueprint $table) {
            $table->dropIndex('teacher_invites_teacher_id_status_index');
        });

        Schema::table('teacher_invites', function (Blueprint $table) {
            $table->string('email')->nullable()->unique()->after('id');
        });

        Schema::table('teacher_invites', function (Blueprint $table) {
            $table->dropColumn('teacher_id');
        });

        Schema::table('teacher_invites', function (Blueprint $table) {
            $table->index(['email', 'status']);
        });
    }
};
