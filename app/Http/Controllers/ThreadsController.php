<?php

namespace App\Http\Controllers;

use App\Filters\ThreadFilters;
use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    //



    public function __construct(){
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel,ThreadFilters $filters)
    {

        $threads = $this->getThreads($channel, $filters);

        if(request()->wantsJson())
            return $threads;



        return view('threads.index',compact('threads'));
    }

    public function show($channelId, Thread $thread)
    {

        return view('threads.show',[
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(25)
        ]);
    }

    public function store(Request $request){
        $this->validate($request,[
           'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'


        ]);

        Thread::create([
            'title' => request('title'),
            'body' =>request('body'),
            'channel_id' => request('channel_id'),
            'user_id' => auth()->user()->id
        ]);

        return redirect('/threads');
    }



    public function create()
    {
        return view('threads.create');
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        if ($channel->exists) {
            $threads = $channel->threads()->latest();
        } else {
            $threads = Thread::latest();
        }

        $threads = $threads->filter($filters);

//        dd($threads->toSql());

        return $threads->get();
    }

    public function destroy($channel, Thread $thread){

        $this->authorize('update',$thread);
//        if($thread->user_id != auth()->user()->id)
//            abort(403, 'you do not have permission to do this');

//        $thread->replies()->delete();
        $thread->delete();

        if(\request()->wantsJson())
        return response([],204);

        return redirect('/threads');
    }

}
