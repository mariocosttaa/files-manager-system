<?php

test('registration screen can be rendered', function () {
    // Register route redirects to landing page where registration modal is shown
    $response = $this->get('/register');

    $response->assertRedirect('/');
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
