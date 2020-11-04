<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Support\Facades\DB;

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

    /** @test */
    function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
    {

//        DB::statement('PRAGMA foreign_keys=on;');

        $this->signIn();

        $reply = create('App\Models\Reply', ['user_id' => auth()->id()]);


        $reply->thread->markBestReply($reply);

        $this->deleteJson(route('replies.destroy', $reply));

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
