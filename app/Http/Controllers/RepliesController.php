<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store($channelId, Thread $thread)
    {
        $this->validate(Request(),[
            'body' => 'required'
        ]);

        $thread->addReply(
            [
                'body' => request('body'),
                'user_id' => auth()->user()->id
            ]
        );

        return back()->with('flash','your reply is added');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    /**
     * Update an existing reply.
     *
     * @param Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), ['body' => 'required']);

        $reply->update(request(['body']));
    }
}
