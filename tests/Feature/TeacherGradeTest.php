<?php

use App\Models\Grade;
use App\Models\Level;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;

beforeEach(function () {
    $this->teacher = User::create([
        'name' => 'Teacher',
        'email' => 'teacher@test.com',
        'password' => bcrypt('password'),
        'role' => 'teacher',
        'status' => 'active',
    ]);

    $this->level = Level::create(['name' => 'Level 1', 'order' => 1]);
    $this->subject = Subject::create([
        'name' => 'Math',
        'max_score' => 100,
        'level_id' => $this->level->id,
        'teacher_id' => $this->teacher->id,
    ]);
    $this->term = Term::create([
        'name' => 'First Term',
        'level_id' => $this->level->id,
        'order' => 1,
        'status' => 'open',
    ]);
    $this->student = Student::create([
        'student_number' => '2026001',
        'name' => 'Student One',
        'level_id' => $this->level->id,
        'status' => 'active',
    ]);
});

test('teacher can view grades page', function () {
    $this->actingAs($this->teacher)
        ->get('/teacher/grades')
        ->assertOk()
        ->assertSee('Math');
});

test('teacher can view grade entry form', function () {
    $this->actingAs($this->teacher)
        ->get('/teacher/grades/entry?' . http_build_query([
            'subject_id' => $this->subject->id,
            'term_id' => $this->term->id,
        ]))
        ->assertOk()
        ->assertSee('Student One');
});

test('teacher can enter grades', function () {
    $response = $this->actingAs($this->teacher)->post('/teacher/grades', [
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'grades' => [
            [
                'student_id' => $this->student->id,
                'score' => 85,
            ],
        ],
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('grades', [
        'student_id' => $this->student->id,
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'score' => 85,
        'status' => 'pending',
    ]);
});

test('grade cannot exceed max score', function () {
    $this->actingAs($this->teacher)->post('/teacher/grades', [
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'grades' => [
            [
                'student_id' => $this->student->id,
                'score' => 150,
            ],
        ],
    ])->assertSessionHasErrors();
});

test('teacher cannot enter grades for closed term', function () {
    $this->term->update(['status' => 'closed']);

    $this->actingAs($this->teacher)->post('/teacher/grades', [
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'grades' => [
            [
                'student_id' => $this->student->id,
                'score' => 80,
            ],
        ],
    ])->assertSessionHasErrors();
});

test('teacher can update existing grade', function () {
    Grade::create([
        'student_id' => $this->student->id,
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'score' => 50,
        'status' => 'pending',
        'entered_by' => $this->teacher->id,
    ]);

    $this->actingAs($this->teacher)->post('/teacher/grades', [
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'grades' => [
            [
                'student_id' => $this->student->id,
                'score' => 90,
            ],
        ],
    ])->assertRedirect();

    $this->assertDatabaseHas('grades', [
        'student_id' => $this->student->id,
        'score' => 90,
    ]);
});

test('teacher cannot access other teachers subject', function () {
    $otherTeacher = User::create([
        'name' => 'Other Teacher',
        'email' => 'other@test.com',
        'password' => bcrypt('password'),
        'role' => 'teacher',
        'status' => 'active',
    ]);

    $this->actingAs($otherTeacher)->post('/teacher/grades', [
        'subject_id' => $this->subject->id,
        'term_id' => $this->term->id,
        'grades' => [
            [
                'student_id' => $this->student->id,
                'score' => 80,
            ],
        ],
    ])->assertNotFound();
});

test('teacher can view dashboard', function () {
    $this->actingAs($this->teacher)
        ->get('/teacher/dashboard')
        ->assertOk();
});

test('non-teacher cannot access teacher pages', function () {
    $visitor = User::create([
        'name' => 'Visitor',
        'email' => 'visitor@test.com',
        'password' => bcrypt('password'),
        'role' => 'visitor',
        'status' => 'active',
    ]);

    $this->actingAs($visitor)
        ->get('/teacher/dashboard')
        ->assertForbidden();
});
