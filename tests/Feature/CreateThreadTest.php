<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Reply;
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

    /** @test */
    function a_thread_can_be_deleted_by_auth_user()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $thread = create(Thread::class, ['user_id' => auth()->user()->id]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);
        $this->json("DELETE", $thread->path())->assertStatus(204);
        $this->assertDatabaseMissing('threads', ['id' => $thread->id])->assertDatabaseMissing('replies', ['id' => $reply->id]);

//        $this->assertDatabaseMissing('activities',['subject_id' => $thread->id, 'subject_type' => get_class($thread)]);
//        $this->assertDatabaseMissing('activities',['subject_id' => $reply->id, 'subject_type' => get_class($reply)]);
        $this->assertDatabaseCount('activities',0);
    }

    /** @test */
    function a_un_auth_can_not_delelte_thread()
    {
//        $this->withoutExceptionHandling();

        $thread = create(Thread::class);
        $this->delete( $thread->path())
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete( $thread->path())
            ->assertStatus(403);

    }

    /** @test */
    function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->unconfirmed()->create();
        $this->signIn($user);

        $thread = make('App\Models\Thread');

        $this->post(route('threads', $thread->toArray()))
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'You must first confirm your email address.');
    }



    public function publishThread($overrides = [])
    {
        $this->signIn();
        $thread = make(Thread::class,$overrides);
        return $this->post('/threads', $thread->toArray());
    }

}
