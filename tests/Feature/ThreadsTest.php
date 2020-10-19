<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function a_user_can_browse_threads()
    {        $thread = Thread::Factory()->create();


        $response = $this->get('/threads');

        $response->assertSee($thread->title);



    }

    /**@test */
    public function a_user_can_view_single_thread(){
        $thread = Thread::Factory()->create();


        $response = $this->get('/threads/' . $thread->id);
        $response->assertSee($thread->title);
    }
}
