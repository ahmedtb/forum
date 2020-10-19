<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();
        $this->thread = Thread::Factory()->create();

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

        $this->get($this->thread->path())->assertOK()->assertSee($reply->body);
    }



}
