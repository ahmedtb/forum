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
        $this->assertEquals([3, 2, 0], array_column($response['data'],'replies_count'));
    }


    /** @test */
    function a_user_can_filter_threads_by_those_not_unanswered()
    {
        $thread = create('App\Models\Thread');
        create('App\Models\Reply', ['thread_id' => $thread->id]);

//        $thread = create('App\Models\Thread');

//        dd($thread->replies_count);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);
    }

    /** @test */
    function a_user_can_request_all_replies_for_a_given_thread()
    {
        $this->withoutExceptionHandling();
        $thread = create(Thread::class);
        create(Reply::class, ['thread_id' => $thread->id]);
        create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
