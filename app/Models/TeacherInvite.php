<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherInvite extends Model
{
    protected $fillable = [
        'teacher_id',
        'name',
        'invited_by',
        'status',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public static function isTeacherIdApproved(string $teacherId): bool
    {
        return self::where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->exists();
    }

    public static function markUsed(string $teacherId): void
    {
        self::where('teacher_id', $teacherId)
            ->where('status', 'pending')
            ->update(['status' => 'used']);
    }
}
