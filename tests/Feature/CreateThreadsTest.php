<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

	function test_guests_may_not_create_threads ()
	{
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$thread = factory('App\Thread')->make();

		$this->post('/threads', $thread->toArray());
	}

    function test_an_authenticated_user_can_create_new_forum_threads ()
	{
		$this->actingAs(factory('App\User')->create());

		$thread = factory('App\Thread')->make();
		$this->post('/threads', $thread->toArray());

		$response = $this->get($thread->path());
		$response->assertSee($thread->title);
		$response->assertSee($thread->body);
	}
}
