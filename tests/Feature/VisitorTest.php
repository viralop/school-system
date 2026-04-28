<?php

test('login page is accessible', function () {
    $this->get('/login')->assertOk()->assertSee('ALWEFAQ');
});

test('register page is accessible', function () {
    $this->get('/register')->assertOk()->assertSee('Create Account');
});

test('teacher signup page is accessible', function () {
    $this->get('/teacher/signup')->assertOk();
});

test('student login page is accessible', function () {
    $this->get('/student/login')->assertOk();
});
