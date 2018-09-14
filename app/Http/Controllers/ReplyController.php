<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Reply;

class ReplyController extends Controller
{
	public function __construct ()
	{
		$this->middleware('auth');
	}

	public function store ($channelId, Thread $thread)
	{
        $this->validate(request(), [
            'body' => 'required'
        ]);

		$thread->addReply([
			'body' => request('body'),
			'user_id' => auth()->id()
		]);

		return back()->with('flash', 'Your reply to this thread is sent!');
	}

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        return back();
	}
}
