<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\PleaseConfirmYourEmail;
use App\Models\User;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        $this->withoutExceptionHandling();

        Mail::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    function user_can_fully_confirm_their_email_addresses()
    {
        $this->withoutExceptionHandling();


        Mail::fake();


        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);
        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads'));

        $this->assertTrue($user->fresh()->confirmed);
    }

    /** @test */
    function confirming_an_invalid_token()
    {
        $this->get(route('register.confirm', ['token' => 'invalid']))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknown token.');
    }
}
