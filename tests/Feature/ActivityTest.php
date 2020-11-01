<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_records_an_activity_when_thread_created()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $this->assertDatabaseHas('activities', [
                'type' => 'created_thread',
                'user_id' => auth()->user()->id,
                'subject_id' => $thread->id,
                'subject_type' => Thread::class
        ]);

        $activity = Activity::first();
        $this->assertEquals($activity->subject->id,$thread->id);
    }

    /** @test */
    function it_records_reply_when_created_as_activity()
    {
        $this->signIn();
        create(Reply::class);
        $this->assertEquals(2,Activity::count());
    }

    /** @test */
    function it_fetchs_feed_for_any_user()
    {

        $this->signIn();
        create(Thread::class,['user_id' => auth()->user()->id] );
        create(Thread::class,['user_id' => auth()->user()->id] );

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());
        $this->assertTrue(
            $feed->keys()->contains(Carbon::now()->format('Y-m-d') )
        );

        $this->assertTrue(
            $feed->keys()->contains(Carbon::now()->subWeek()->format('Y-m-d') )
        );
    }
}
