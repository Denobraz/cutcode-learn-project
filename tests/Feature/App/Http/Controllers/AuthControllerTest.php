<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Listeners\SendEmailNewUserListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_success(): void
    {
        $this->get(action([AuthController::class, 'login']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    public function test_signup_page_success(): void
    {
        $this->get(action([AuthController::class, 'signup']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.signup');
    }

    public function test_logout_success(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete(action([AuthController::class, 'logout']))
            ->assertRedirect(route('home'));
    }

    public function test_forgot_password_page_success(): void
    {
        $this->get(action([AuthController::class, 'forgotPassword']))
            ->assertOk()
            ->assertSee('Забыли пароль')
            ->assertViewIs('auth.forgot-password');
    }

    public function test_sign_in_success(): void
    {
        $password = 'password1234';
        $email = 'test@mail.ru';
        $user = User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        $request = [
            'email' => $user->email,
            'password' => $password
        ];

        $response = $this->post(action([AuthController::class, 'authenticate']), $request);

        $response->assertValid()->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_register_success(): void
    {
        Notification::fake();
        Event::fake();

        $request = [
            'name' => 'John Doe',
            'email' => 'test@mail.ru',
            'password' => 'password1234',
            'password_confirmation' => 'password1234',
        ];

        $this->assertDatabaseMissing('users', [
            'email' => $request['email']
        ]);

        $response = $this->post(action([AuthController::class, 'register']), $request);

        $response->assertValid();

        $this->assertDatabaseHas('users', [
            'email' => $request['email']
        ]);

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendEmailNewUserListener::class);

        $user = User::query()->where('email', $request['email'])->first();
        $event = new Registered($user);
        $listener = new SendEmailNewUserListener();
        $listener->handle($event);

        Notification::assertSentTo($user,NewUserNotification::class);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('home'));
    }
}
