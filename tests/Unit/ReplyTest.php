<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Carbon\Carbon;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_reply_has_owner()
    {
        $reply = Reply::factory()->create();
        $this->assertInstanceOf(User::class, $reply->owner);
    }

    /** @test */
    function it_knows_if_it_was_just_published()
    {
        $reply = create('App\Models\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = new Reply([
            'body' => '@JaneDoe wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }


    /** @test */
    function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new Reply([
            'body' => 'Hello @Jane-Doe.'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>.',
            $reply->body
        );

    }

    /** @test */
    function it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Models\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());

    }
}
