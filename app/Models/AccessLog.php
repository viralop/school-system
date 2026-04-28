<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class AccessLog extends Model
{
    protected $fillable = [
        'secure_link_id',
        'user_id',
        'action',
        'success',
        'ip_address',
        'user_agent',
        'failure_reason',
        'accessed_at',
    ];

    protected function casts(): array
    {
        return [
            'success' => 'boolean',
            'accessed_at' => 'datetime',
        ];
    }

    public function secureLink(): BelongsTo
    {
        return $this->belongsTo(SecureLink::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function logAttempt(
        Request $request,
        string $action,
        bool $success,
        ?int $secureLinkId = null,
        ?int $userId = null,
        ?string $failureReason = null,
    ): self {
        return self::create([
            'secure_link_id' => $secureLinkId,
            'user_id' => $userId,
            'action' => $action,
            'success' => $success,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'failure_reason' => $failureReason,
            'accessed_at' => now(),
        ]);
    }
}
