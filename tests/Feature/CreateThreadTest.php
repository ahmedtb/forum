<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;


    /** @test */
    function guest_may_not_create_threads()
    {
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    function a_user_cannot_see_thread_create_form()
    {
        $this->get('threads/create')->assertRedirect('/login');
    }

    /** @test */
    function a_user_can_create_thread()
    {
        $this->withoutExceptionHandling();

        $this->signIn();
        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())->assertSee($thread->title)->assertSee($thread->body);
    }

}
