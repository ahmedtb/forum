<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $threads = Thread::latest()->get();
        return view('threads.index',compact('threads'));
    }

    public function show(Thread $thread)
    {
        return view('threads.show',compact('thread'));
    }

    public function store(){
        Thread::create([
            'title' => request('title'),
            'body' =>request('body'),
            'user_id' => auth()->user()->id
        ]);

        return redirect('/threads');
    }

    public function create()
    {
        return view('threads.create');
    }
}
