<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonthlyExam extends Model
{
    protected $fillable = ['name_en', 'name_ar', 'date', 'level_id', 'status'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function getLocalizedNameAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->name_ar ? $this->name_ar : $this->name_en;
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(MonthlyExamSubject::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'exam_id')->where('exam_type', 'monthly');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    public function close(): void
    {
        $this->update(['status' => 'closed']);
    }

    public function open(): void
    {
        $this->update(['status' => 'open']);
    }

    public function totalFullMark(): float
    {
        return $this->subjects()->sum('max_degree');
    }
}
