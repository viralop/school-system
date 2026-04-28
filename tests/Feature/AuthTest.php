<?php

use App\Models\User;

beforeEach(function () {
    $this->supervisor = User::create([
        'name' => 'Supervisor',
        'email' => 'supervisor@test.com',
        'password' => bcrypt('password123'),
        'role' => 'supervisor',
        'status' => 'active',
    ]);
});

test('visitor can register', function () {
    $response = $this->post('/register', [
        'name' => 'Visitor',
        'email' => 'visitor@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertDatabaseHas('users', [
        'email' => 'visitor@test.com',
        'role' => 'visitor',
    ]);
    $this->assertAuthenticated();
});

test('registration requires valid data', function () {
    $this->post('/register', [
        'name' => '',
        'email' => 'not-email',
        'password' => 'short',
        'password_confirmation' => 'different',
    ])->assertSessionHasErrors(['name', 'email', 'password']);
});

test('registration prevents duplicate email', function () {
    $this->post('/register', [
        'name' => 'Test',
        'email' => 'supervisor@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors(['email']);
});

test('user can login with valid credentials', function () {
    $response = $this->post('/login', [
        'email' => 'supervisor@test.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('supervisor.dashboard'));
    $this->assertAuthenticated();
});

test('supervisor redirects to supervisor dashboard', function () {
    $this->post('/login', [
        'email' => 'supervisor@test.com',
        'password' => 'password123',
    ])->assertRedirect(route('supervisor.dashboard'));
});

test('teacher redirects to teacher dashboard', function () {
    User::create([
        'name' => 'Teacher',
        'email' => 'teacher@test.com',
        'password' => bcrypt('password123'),
        'role' => 'teacher',
        'status' => 'active',
    ]);

    $this->post('/login', [
        'email' => 'teacher@test.com',
        'password' => 'password123',
    ])->assertRedirect(route('teacher.dashboard'));
});

test('visitor redirects to home', function () {
    User::create([
        'name' => 'Visitor',
        'email' => 'visitor@test.com',
        'password' => bcrypt('password123'),
        'role' => 'visitor',
        'status' => 'active',
    ]);

    $this->post('/login', [
        'email' => 'visitor@test.com',
        'password' => 'password123',
    ])->assertRedirect(route('home'));
});

test('login fails with wrong password', function () {
    $this->post('/login', [
        'email' => 'supervisor@test.com',
        'password' => 'wrongpassword',
    ])->assertSessionHasErrors(['email']);

    $this->assertGuest();
});

test('login fails with non-existent email', function () {
    $this->post('/login', [
        'email' => 'nobody@test.com',
        'password' => 'password123',
    ])->assertSessionHasErrors(['email']);

    $this->assertGuest();
});

test('frozen user cannot login', function () {
    $frozen = User::create([
        'name' => 'Frozen Teacher',
        'email' => 'frozen@test.com',
        'password' => bcrypt('password123'),
        'role' => 'teacher',
        'status' => 'frozen',
    ]);

    $this->post('/login', [
        'email' => 'frozen@test.com',
        'password' => 'password123',
    ])->assertSessionHasErrors(['email']);

    $this->assertGuest();
});

test('user can logout', function () {
    $this->actingAs($this->supervisor);

    $response = $this->post('/logout');

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

test('login page is accessible', function () {
    $this->get('/login')->assertOk()->assertSee('ALWEFAQ');
});

test('register page is accessible', function () {
    $this->get('/register')->assertOk()->assertSee('Create Account');
});
