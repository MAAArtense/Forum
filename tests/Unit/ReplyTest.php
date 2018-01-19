<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReplyTest extends TestCase
{
	use DatabaseMigrations;

	protected $reply;

	public function setUp()
	{
		parent::setUp();

		$this->reply = factory('App\Reply')->create();
	}

    function test_if_it_has_a_creator()
	{
		$this->assertInstanceOf('App\User', $this->reply->owner);
	}
}
