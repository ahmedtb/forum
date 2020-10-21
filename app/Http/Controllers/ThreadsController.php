<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel)
    {
        if($channel->exists)
        {
//            $channelId = Channel::where('slug',$channelSlug)->first()->id;
//            $threads = Thread::where('channel_id',$channelId)->latest()->get();
            $threads = $channel->threads()->latest()->get();
        }else{
            $threads = Thread::latest()->get();

        }

        return view('threads.index',compact('threads'));
    }

    public function show($channelId, Thread $thread)
    {
        return view('threads.show',compact('thread'));
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
}
