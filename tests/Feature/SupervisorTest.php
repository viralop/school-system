<?php

use App\Models\Level;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\Grade;
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
});

test('supervisor can view dashboard', function () {
    $this->actingAs($this->supervisor)
        ->get('/supervisor/dashboard')
        ->assertOk()
        ->assertSee('Dashboard');
});

test('supervisor can add student without section', function () {
    $response = $this->actingAs($this->supervisor)->post('/supervisor/students', [
        'name' => 'Ahmed',
        'parent_phone' => '+201234567890',
        'level_id' => $this->level->id,
        'section_id' => null,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('students', [
        'name' => 'Ahmed',
        'level_id' => $this->level->id,
        'section_id' => null,
    ]);
});

test('supervisor can add student with section', function () {
    $section = Section::create(['name' => 'A', 'level_id' => $this->level->id]);

    $response = $this->actingAs($this->supervisor)->post('/supervisor/students', [
        'name' => 'Sara',
        'parent_phone' => '+201234567891',
        'level_id' => $this->level->id,
        'section_id' => $section->id,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('students', [
        'name' => 'Sara',
        'section_id' => $section->id,
    ]);
});

test('student number is auto generated and unique', function () {
    $s1 = Student::create([
        'student_number' => $this->level->nextStudentNumber(),
        'name' => 'Student 1',
        'level_id' => $this->level->id,
        'status' => 'active',
    ]);

    $s2 = Student::create([
        'student_number' => $this->level->nextStudentNumber(),
        'name' => 'Student 2',
        'level_id' => $this->level->id,
        'status' => 'active',
    ]);

    expect($s1->student_number)->not->toBe($s2->student_number);
});

test('supervisor can add subject', function () {
    $teacher = User::create([
        'name' => 'Teacher',
        'email' => 'teacher@test.com',
        'password' => bcrypt('password'),
        'role' => 'teacher',
        'status' => 'active',
    ]);

    $response = $this->actingAs($this->supervisor)->post('/supervisor/subjects', [
        'name' => 'Math',
        'max_score' => 100,
        'level_id' => $this->level->id,
        'teacher_id' => $teacher->id,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('subjects', [
        'name' => 'Math',
        'level_id' => $this->level->id,
        'teacher_id' => $teacher->id,
    ]);
});

test('supervisor can add term to all levels', function () {
    Level::create(['name' => 'Level 2', 'order' => 2]);
    Level::create(['name' => 'Level 3', 'order' => 3]);

    $response = $this->actingAs($this->supervisor)->post('/supervisor/terms', [
        'name' => 'First Term',
    ]);

    $response->assertRedirect();
    expect(Term::where('name', 'First Term')->count())->toBe(3);
});

test('supervisor can freeze and unfreeze student', function () {
    $student = Student::create([
        'student_number' => '2026001',
        'name' => 'Test Student',
        'level_id' => $this->level->id,
        'status' => 'active',
    ]);

    $this->actingAs($this->supervisor)
        ->patch("/supervisor/students/{$student->id}/freeze")
        ->assertRedirect();

    expect($student->fresh()->status)->toBe('frozen');

    $this->actingAs($this->supervisor)
        ->patch("/supervisor/students/{$student->id}/unfreeze")
        ->assertRedirect();

    expect($student->fresh()->status)->toBe('active');
});

test('supervisor can delete student', function () {
    $student = Student::create([
        'student_number' => '2026002',
        'name' => 'Delete Me',
        'level_id' => $this->level->id,
        'status' => 'active',
    ]);

    $this->actingAs($this->supervisor)
        ->delete("/supervisor/students/{$student->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('students', ['id' => $student->id]);
});

test('supervisor can view subjects with pending grade dot', function () {
    $this->actingAs($this->supervisor)
        ->get('/supervisor/subjects')
        ->assertOk();
});

test('supervisor can view subject grades', function () {
    $subject = Subject::create([
        'name' => 'Science',
        'max_score' => 50,
        'level_id' => $this->level->id,
    ]);

    $this->actingAs($this->supervisor)
        ->get("/supervisor/subjects/{$subject->id}/grades")
        ->assertOk();
});

test('non-supervisor cannot access supervisor pages', function () {
    $teacher = User::create([
        'name' => 'Teacher',
        'email' => 'teacher@test.com',
        'password' => bcrypt('password'),
        'role' => 'teacher',
        'status' => 'active',
    ]);

    $this->actingAs($teacher)
        ->get('/supervisor/dashboard')
        ->assertForbidden();
});
