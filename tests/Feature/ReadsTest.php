<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp() : void
    {
        parent::setUp();
        $this->thread = create(Thread::class);

    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function single_thread(){

        $this->get($this->thread->path())->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_view_replies_associated_with_thread()
    {
        $reply = Reply::factory()->create(['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())->assertSee($reply->body);
    }


    /** @test */
    function a_user_can_fillter_through_channels()
    {
        $this->withoutExceptionHandling();
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class,['channel_id' => $channel->id]);
        $threadNotInChannel = create(Thread::class);

//        dd($threadNotInChannel->title);
        $this->get('/threads/'.$channel->slug)->assertDontSee($threadNotInChannel->title)->assertSee($threadInChannel->title);

    }

    /** @test */
    function a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create(User::class,['name' =>  'JohnDoe']) );
        $threadbyJohn = create(Thread::class,['user_id' => auth()->user()->id]);
        $threadNotByJohn = create(Thread::class);

        $this->get('/threads?by=JohnDoe')->assertSee($threadbyJohn->title)->assertDontSee($threadNotByJohn->title);

    }

    /** @test */
    function a_user_can_filter_by_popularity()
    {
        $threadWtihTwoReplies = create(Thread::class);
        create(Reply::class,['thread_id' => $threadWtihTwoReplies->id]);
        create(Reply::class,['thread_id' => $threadWtihTwoReplies->id]);

        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class,['thread_id'=> $threadWithThreeReplies->id]);
        create(Reply::class,['thread_id'=> $threadWithThreeReplies->id]);
        create(Reply::class,['thread_id'=> $threadWithThreeReplies->id]);

        $threadWithNoReplies = $this->thread;

        $response = $this->getJson('/threads?popular=1')->json();
        $this->assertEquals([3, 2, 0], array_column($response,'replies_count'));
    }
}
