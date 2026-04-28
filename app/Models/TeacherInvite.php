<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherInvite extends Model
{
    protected $fillable = [
        'email',
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

    public static function isEmailApproved(string $email): bool
    {
        return self::where('email', $email)
            ->where('status', 'pending')
            ->exists();
    }

    public static function markUsed(string $email): void
    {
        self::where('email', $email)
            ->where('status', 'pending')
            ->update(['status' => 'used']);
    }
}
