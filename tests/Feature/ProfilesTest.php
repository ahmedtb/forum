<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfilesTest extends TestCase
{use  DatabaseMigrations;
    /**
     * @test
     */
    function a_user_can_view_profiles()
    {
        $this->withoutExceptionHandling();
        $user = create(User::class);
        $response = $this->get('profiles/' . $user->name);
        $response->assertSee($user->name);
    }
    /**
     * @test
     */
    function a_profile_of_user_display_his_threads(){
        $this->signIn();
        $user = auth()->user();
        $thread = create(Thread::class,['user_id' => $user->id]);

        $this->get('profiles/' . $user->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
