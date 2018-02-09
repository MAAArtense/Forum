<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
	function test_guests_may_not_create_threads ()
	{
		$this->withExceptionHandling();

		$this->post('/threads')
			->assertRedirect('login');

		$this->get('/threads/create')
			->assertRedirect('login');
	}

    function test_an_authenticated_user_can_create_new_forum_threads ()
	{
		$this->signIn();

		$thread = create('App\Thread');
		$this->post('/threads', $thread->toArray());

		$response = $this->get($thread->path());
		$response->assertSee($thread->title);
		$response->assertSee($thread->body);
	}
}
