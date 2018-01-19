<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForum extends TestCase
{
	use DatabaseMigrations;

	public function test_unauthenticated_users_may_not_add_replies ()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');

		$this->post('/threads/1/replies', []);
	}

    public function test_an_authenticated_user_may_participate_in_forum_threads ()
    {
		//Be() = set the current auth user to this user (logged user)
		$this->be(factory('App\User')->create());

	 	$thread = factory('App\Thread')->create();
		$reply = factory('App\Reply')->make();

		$this->post($thread->path() . '/replies', $reply->toArray());

		$response = $this->get($thread->path());
		$response->assertSee($reply->body);
    }
}
