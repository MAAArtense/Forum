<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    //use DatabaseMigrations;

	protected $thread;

	public function setUp()
	{
		parent::setUp();

		$this->thread = create('App\Thread');
	}

    public function test_a_user_can_view_all_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
	}

	public function test_a_user_can_read_a_single_thread()
    {
		$response = $this->get($this->thread->path());
		$response->assertSee($this->thread->title);
	}

	public function test_a_user_can_read_replies_that_are_associated_with_a_thread()
	{
		//een user moet ingelogd zijn
		$this->be(create('App\User'));
		//moet die een thrread
		$reply = create('App\Reply', ['thread_id' => $this->thread->id]);

		$response = $this->get($this->thread->path());
		$response->assertSee($reply->body);
	}
}
