<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostForm;
use App\Models\Reply;
use App\Models\Thread;

use Illuminate\Support\Facades\Gate;

class RepliesController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }


    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }

    public function store($channelId, Thread $thread, CreatePostForm $form)
    {

//        if (Gate::denies('create', new Reply)) {
//            return response(
//                'You are posting too frequently. Please take a break. :)', 429
//            );
//        }



//            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply =  $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);



            return $reply->load('owner');

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

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

            $this->validate(request(), ['body' => 'required|spamfree']);
            $reply->update(request(['body']));

    }


}
