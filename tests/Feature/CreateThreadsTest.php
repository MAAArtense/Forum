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

		$thread = make('App\Thread');

		$response = $this->post('/threads', $thread->toArray());

		$this->get($response->headers->get('location'))
			->assertSee($thread->title)
			->assertSee($thread->body);
	}

	function test_a_thread_requires_a_title ()
	{
	    $this->publishThread(['title' => null])
			->assertSessionHasErrors('title');
	}

    function test_a_thread_requires_a_body ()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    function test_a_thread_requires_a_valid_channel ()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    function publishThread($overrides)
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
