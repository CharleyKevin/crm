<?php

use App\Livewire\Auth\Login;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertOk();
});

it('should be able to login', function () {
    $user = \App\Models\User::factory()->create([
        'email'    => 'joe@doe.com',
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'joe@doe.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasNoErrors()
        ->assertRedirect(route('welcome'));

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);

});

it('should make sure to inform the user an error when email and password doesnt work', function () {
    Livewire::test(Login::class)
        ->set('email', 'joe@doe.com')
        ->set('password', 'password')
        ->call('tryToLogin')
        ->assertHasErrors(['invalidCredentials'])
        ->assertSee(trans('auth.failed'));
});
