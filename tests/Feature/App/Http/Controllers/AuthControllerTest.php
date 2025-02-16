<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Listeners\SendEmailNewUserListener;
use App\Notifications\NewUserNotification;
use Domain\Auth\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_success(): void
    {
        $this->get(action([SignInController::class, 'page']))
            ->assertOk()
            ->assertSee('Вход в аккаунт')
            ->assertViewIs('auth.login');
    }

    public function test_signup_page_success(): void
    {
        $this->get(action([SignUpController::class, 'page']))
            ->assertOk()
            ->assertSee('Регистрация')
            ->assertViewIs('auth.signup');
    }

    public function test_logout_success(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete(action([SignInController::class, 'logout']))
            ->assertRedirect(route('home'));
    }

    public function test_forgot_password_page_success(): void
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
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

        $response = $this->post(action([SignInController::class, 'handle']), $request);

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

        $response = $this->post(action([SignUpController::class, 'handle']), $request);

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

    public function test_send_reset_link_email_success()
    {
        $email = 'test@mail.ru';
        $user = User::factory()->create([
            'email' => $email
        ]);

        Notification::fake();

        $response = $this->post(action([ForgotPasswordController::class, 'handle']), ['email' => $email]);

        $response->assertValid();

        Notification::assertSentTo($user, ResetPassword::class);
    }
}
