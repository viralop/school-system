<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MonthlyExamSubject extends Pivot
{
    public $timestamps = true;

    protected $table = 'monthly_exam_subjects';

    protected $fillable = ['monthly_exam_id', 'subject_id', 'max_degree'];

    protected function casts(): array
    {
        return [
            'max_degree' => 'decimal:2',
        ];
    }

    public function monthlyExam(): BelongsTo
    {
        return $this->belongsTo(MonthlyExam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'subject_id', 'subject_id')
            ->where('exam_type', 'monthly')
            ->whereColumn('exam_id', 'monthly_exam_id');
    }
}
