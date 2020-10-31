<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{use DatabaseMigrations;

/** @test */
    function a_non_authenticated_user_can_not_reply()
    {
//        $this->withoutExceptionHandling();
//        $this->withExceptionHandling();
        $this->post('/threads/somechannel/1/replies',[])->assertRedirect('/login');
    }

    /** @test */
    function a_authenticated_user_can_reply()
    {
        $this->withoutExceptionHandling();

        $thread = Thread::factory()->create();

        $user = User::factory()->create();
        $this->be($user);

        $reply = Reply::factory()->make();

        $this->post($thread->path() . '/replies', $reply->toArray());

        //$this->get($thread->path())->assertSee($reply->body);
        $this->assertDatabaseHas('replies',['body' => $reply->body]);
        $this->assertEquals(1,$thread->refresh()->replies_count);

    }

    /** @test */
    function a_reply_requires_body()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $reply = make(Reply::class,['body' => null]);
        $thread = create(Thread::class);


        $response = $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_unauth_user_can_not_delete_reply ()
    {
//        $this->withoutExceptionHandling();
        $reply = create(Reply::class);
        $this->delete('/replies/' . $reply->id)
            ->assertRedirect('login');

        $this->signIn()
            ->delete('/replies/' . $reply->id)
            ->assertStatus(403);
    }

    /** @test */
    function a_user_can_delete_his_reply()
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);
        $this->delete('/replies/' . $reply->id)->assertStatus(302);
        $this->assertDatabaseMissing('replies',['id' => $reply->id]);
        $this->assertEquals(0,$reply->thread->fresh()->replies_count);
    }

    /** @test */
    function a_unauth_user_can_not_update_reply ()
    {
//        $this->withoutExceptionHandling();
        $reply = create(Reply::class);
        $this->patch('/replies/' . $reply->id)
            ->assertRedirect('login');

        $this->signIn()
            ->patch('/replies/' . $reply->id)
            ->assertStatus(403);
    }

    /** @test */
    function a_authorized_users_can_update_replies()
    {
        $this->signIn();
        $reply = create(Reply::class, ['user_id' => auth()->id()]);
        $updated = 'this is updated reply bidy';
        $this->patch('/replies/' . $reply->id, ['body'=> $updated]);
        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updated]);
    }

    /** @test */
    function replies_that_contain_spam_may_not_be_created()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class, [
            'body' => 'Yahoo Customer Support'
        ]);

//        $this->expectException(\Exception::class);

        $this->post($thread->path() . '/replies', $reply->toArray())->assertStatus(422);
    }

    /** @test */
    function users_may_only_reply_a_maximum_of_once_per_minute()
    {
        $this->signIn();

        $thread = create('App\Models\Thread');
        $reply = make('App\Models\Reply');

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(Response::HTTP_CREATED);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(429);
    }


}



