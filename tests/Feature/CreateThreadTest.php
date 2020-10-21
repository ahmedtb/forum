<?php

namespace Tests\Feature;

use App\Models\Channel;
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


        $this->post('/threads')->assertRedirect('/login');

        $this->get('threads/create')->assertRedirect('/login');
    }


    /** @test */
    function a_user_can_create_thread()
    {
        $this->withoutExceptionHandling();

        $this->signIn();
         $thread = make(Thread::class);

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))->assertSee($thread->title)->assertSee($thread->body);
    }

    /** @test */
    function a_thread_requires_title()
    {
//        $this->withoutExceptionHandling();

       $this->publishThread(['title' => null])->assertSessionHasErrors('title');
    }
/** @test */
    function a_thread_requires_body()
    {

        $this->publishThread(['body' => null])->assertSessionHasErrors('body');
    }
    /** @test */
    function a_thread_requires_valid_channel()
    {
        Channel::factory()->count(2)->create();
        $this->publishThread(['channel_id' => null])->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides)
    {
        $this->signIn();
        $thread = make(Thread::class,$overrides);
        return $this->post('/threads', $thread->toArray());
    }

}
