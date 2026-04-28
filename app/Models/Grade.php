<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'term_id',
        'score',
        'status',
        'entered_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'approved_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve(int $supervisorId): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $supervisorId,
            'approved_at' => now(),
        ]);
    }

    public function reject(int $supervisorId): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $supervisorId,
            'approved_at' => now(),
        ]);
    }
}
