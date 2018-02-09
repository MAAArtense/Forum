<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
	function test_guests_may_not_create_threads ()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$thread = make('App\Thread');

		$this->post('/threads', $thread->toArray());
	}

    function test_an_authenticated_user_can_create_new_forum_threads ()
	{
		$this->signIn();

		$thread = make('App\Thread');
		$this->post('/threads', $thread->toArray());

		$response = $this->get($thread->path());
		$response->assertSee($thread->title);
		$response->assertSee($thread->body);
	}
}
