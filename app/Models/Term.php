<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    protected $fillable = ['name', 'name_ar', 'level_id', 'order', 'status'];

    public function getLocalizedNameAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->name_ar ? $this->name_ar : $this->name;
    }

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function isGraded(): bool
    {
        return $this->status === 'graded';
    }

    public function open(): void
    {
        $this->update(['status' => 'open']);
    }

    public function close(): void
    {
        $this->update(['status' => 'closed']);
    }

    public function markGraded(): void
    {
        $this->update(['status' => 'graded']);
    }
}
