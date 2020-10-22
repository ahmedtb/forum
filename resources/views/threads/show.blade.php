@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#"> {{$thread->creator->name }} </a> posted:
                        {{ $thread->title }}
                    </div>

                    <div class="card-body">
                        {{ $thread->body }}
                    </div>
                </div>

                <div class="col-md-8">

                    @foreach($replies as $reply)
                        @include('threads.reply')
                    @endforeach

                        {{$replies->links()}}
                </div>





                @if(auth()->check())

                    <div class="col-md-8">

                        <form method="post" action="{{ $thread->path() }}./replies">
                            @csrf
                            <div class="card-body">
                            <textarea name="body" id="body" class="form-control" placeholder="Have something to say?"
                                      rows="5"> </textarea>
                            </div>

                            <button type="submit" class="btn btn-default">Post</button>
                        </form>

                    </div>

                @else
                    <p class="text-center">please <a href="{{route('login')}}">sign in</a> to the log in page.</p>
                @endif
            </div>


            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>this post was created at {{$thread->created_at->diffForHumans()}} ago by:
                            <a href="#">{{$thread->creator->name}}</a> and currently has
                            {{$thread->replies_count}} {{Illuminate\Support\str::plural('comment',$thread->replies_count)}}
                            ..
                        </p>
                    </div>
                </div>
            </div>

        </div>


    </div>


@endsection
