<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'student_number',
        'name',
        'parent_phone',
        'level_id',
        'section_id',
        'status',
    ];

    protected function casts(): array
    {
        return [];
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function isFrozen(): bool
    {
        return $this->status === 'frozen';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function freeze(): void
    {
        $this->update(['status' => 'frozen']);
    }

    public function unfreeze(): void
    {
        $this->update(['status' => 'active']);
    }
}
