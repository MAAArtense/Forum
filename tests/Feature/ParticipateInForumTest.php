<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForum extends TestCase
{
	//use DatabaseMigrations;

	public function test_unauthenticated_users_may_not_add_replies ()
	{
		$this->withExceptionHandling()
			->post('/threads/somechannel/1/replies', [])
			->assertRedirect('/login');
	}

    public function test_an_authenticated_user_may_participate_in_forum_threads ()
    {
		//Be() = set the current auth user to this user (logged user)
		$this->be(create('App\User'));

	 	$thread = create('App\Thread');
		$reply = make('App\Reply');

		$this->post($thread->path() . '/replies', $reply->toArray());

		$response = $this->get($thread->path());
		$response->assertSee($reply->body);
    }

    public function test_a_reply_requires_a_body ()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    public function test_unauthorized_users_cannot_delete_replies ()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    public function test_authorized_users_can_delete_replies ()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }
}
