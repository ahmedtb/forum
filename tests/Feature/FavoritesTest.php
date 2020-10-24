<?php

namespace Tests\Feature;

use App\Models\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoritesTest extends TestCase
{use DatabaseMigrations;

/** @test */
    function a_guest_cannot_favorite_anything()
    {
//        $this->withoutExceptionHandling();

        $response = $this->post('/Replies/1/favorites')->assertRedirect('/login');
    }

/** @test */
    function a_auth_user_can_favorite_reply()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $reply = create(Reply::class);

        $this->post('/Replies/' . $reply->id . '/favorites');

        $this->assertCount(1,$reply->favorites);
    }
/** @test */
    function a_auth_user_can_favorite_reply_only_once()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $reply = create(Reply::class);

            $this->post('/Replies/' . $reply->id . '/favorites');
            $this->post('/Replies/' . $reply->id . '/favorites');

        $this->assertCount(1,$reply->favorites);
    }
}
