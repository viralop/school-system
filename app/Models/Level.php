<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = ['name', 'order'];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('name');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function terms(): HasMany
    {
        return $this->hasMany(Term::class)->orderBy('order');
    }

    public function nextStudentNumber(): string
    {
        $lastNumber = Student::where('student_number', 'like', now()->year . '%')
            ->max('student_number');

        $sequence = $lastNumber
            ? (int) substr($lastNumber, -3) + 1
            : 1;

        return now()->year . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}
