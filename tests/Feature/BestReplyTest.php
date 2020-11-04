<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create('App\Models\Thread', ['user_id' => auth()->id()]);

        $replies[0] = create('App\Models\Reply', ['thread_id' => $thread->id]);
        $replies[1] = create('App\Models\Reply', ['thread_id' => $thread->id]);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', [$replies[1]->id]));

//        dd($replies);

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    function only_the_thread_creator_may_mark_a_reply_as_best()
    {
//        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create('App\Models\Thread', ['user_id' => auth()->id()]);

        $replies[0] = create('App\Models\Reply', ['thread_id' => $thread->id]);
        $replies[1] = create('App\Models\Reply', ['thread_id' => $thread->id]);

        $this->signIn(create('App\Models\User'));

        $this->postJson(route('best-replies.store', [$replies[1]->id]))->assertStatus(403);

        $this->assertFalse($replies[1]->fresh()->isBest());
    }
}
