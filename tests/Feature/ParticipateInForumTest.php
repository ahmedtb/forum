<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{use DatabaseMigrations;

/** @test */
    function a_non_authenticated_user_can_not_reply()
    {
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post('/threads/1/replies',[]);
    }

    /** @test */
    function a_authenticated_user_can_reply()
    {
        $this->withoutExceptionHandling();

        $thread = Thread::factory()->create();

        $user = User::factory()->create();
        $this->be($user);

        $reply = Reply::factory()->make();
//        $this->assertNotEquals($reply->owner->id,$user->id);

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())->assertSee($reply->body);
    }
}
