<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('access_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('secure_link_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->boolean('success')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('failure_reason')->nullable();

            $table->timestamp('accessed_at');
            $table->timestamps();

            $table->index(['secure_link_id', 'accessed_at']);
            $table->index('ip_address');
            $table->index('accessed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};
