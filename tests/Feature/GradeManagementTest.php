<?php

use App\Models\Grade;
use App\Models\Level;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;

beforeEach(function () {
    $this->supervisor = User::create([
        'name' => 'Supervisor',
        'email' => 'sup@test.com',
        'password' => bcrypt('password'),
        'role' => 'supervisor',
        'status' => 'active',
    ]);

    $this->level = Level::create(['name' => 'Level 1', 'order' => 1]);
    $this->subject = Subject::create([
        'name' => 'Math',
        'max_score' => 100,
        'level_id' => $this->level->id,
    ]);
    $this->term = Term::create([
        'name' => 'First Term',
        'level_id' => $this->level->id,
        'order' => 1,
        'status' => 'open',
    ]);
    $this->student = Student::create([
        'student_number' => '2026001',
        'name' => 'Student',
        'level_id' => $this->level->id,
        'status' => 'active',
    ]);
});

test('supervisor can approve grade', function () {
    $grade = Grade::create([
        'student_id' => $this->student->id,
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'score' => 85,
        'status' => 'pending',
        'entered_by' => 1,
    ]);

    $this->actingAs($this->supervisor)
        ->patch("/supervisor/grades/{$grade->id}/approve")
        ->assertRedirect();

    expect($grade->fresh()->status)->toBe('approved');
    expect($grade->fresh()->approved_by)->toBe($this->supervisor->id);
});

test('supervisor can reject grade', function () {
    $grade = Grade::create([
        'student_id' => $this->student->id,
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'score' => 85,
        'status' => 'pending',
        'entered_by' => 1,
    ]);

    $this->actingAs($this->supervisor)
        ->patch("/supervisor/grades/{$grade->id}/reject")
        ->assertRedirect();

    expect($grade->fresh()->status)->toBe('rejected');
});

test('supervisor can bulk approve by subject', function () {
    $grade1 = Grade::create([
        'student_id' => $this->student->id,
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'score' => 80,
        'status' => 'pending',
        'entered_by' => 1,
    ]);

    $student2 = Student::create([
        'student_number' => '2026002',
        'name' => 'Student 2',
        'level_id' => $this->level->id,
        'status' => 'active',
    ]);

    $grade2 = Grade::create([
        'student_id' => $student2->id,
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'score' => 90,
        'status' => 'pending',
        'entered_by' => 1,
    ]);

    $this->actingAs($this->supervisor)
        ->post('/supervisor/grades/bulk-approve', [
            'subject_id' => $this->subject->id,
        ])
        ->assertRedirect();

    expect($grade1->fresh()->status)->toBe('approved');
    expect($grade2->fresh()->status)->toBe('approved');
});

test('supervisor can view results page', function () {
    $this->actingAs($this->supervisor)
        ->get('/supervisor/results?level_id=' . $this->level->id)
        ->assertOk();
});
