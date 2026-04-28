<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SecureLink extends Model
{
    protected $fillable = [
        'token_hash',
        'purpose',
        'user_id',
        'max_uses',
        'times_used',
        'expires_at',
        'used_at',
        'metadata',
    ];

    protected $hidden = [
        'token_hash',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
            'max_uses' => 'integer',
            'times_used' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(AccessLog::class);
    }

    public static function generate(
        string $purpose,
        ?int $userId = null,
        ?int $expiresMinutes = null,
        int $maxUses = 1,
        ?string $metadata = null,
    ): array {
        $plainToken = Str::random(64);

        $secureLink = self::create([
            'token_hash' => bcrypt($plainToken),
            'purpose' => $purpose,
            'user_id' => $userId,
            'max_uses' => $maxUses,
            'times_used' => 0,
            'expires_at' => $expiresMinutes ? now()->addMinutes($expiresMinutes) : null,
            'metadata' => $metadata,
        ]);

        return [
            'secure_link' => $secureLink,
            'plain_token' => $plainToken,
            'url' => url("/secure/{$secureLink->id}/{$plainToken}"),
        ];
    }

    public static function validateToken(int $linkId, string $plainToken): ?self
    {
        $link = self::where('id', $linkId)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (! $link) {
            return null;
        }

        if (! password_verify($plainToken, $link->token_hash)) {
            return null;
        }

        if ($link->times_used >= $link->max_uses) {
            return null;
        }

        return $link;
    }

    public function consume(): bool
    {
        if ($this->times_used >= $this->max_uses) {
            return false;
        }

        $this->increment('times_used');

        if ($this->times_used >= $this->max_uses) {
            $this->update(['used_at' => now()]);
        }

        return true;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isExhausted(): bool
    {
        return $this->times_used >= $this->max_uses;
    }

    public function isValid(): bool
    {
        return ! $this->isExpired() && ! $this->isExhausted();
    }
}
