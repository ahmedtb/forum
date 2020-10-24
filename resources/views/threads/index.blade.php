@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Forum Threads</div>

                    <div class="card-body">
                        @foreach($threads as $thread)
                            <article>
                                <div class="level">

                                <a href="{{ $thread->path() }}" class="flex" > <h4 >{{ $thread->title }}</h4> </a>

                                    <strong>{{$thread->replies_count}} {{Illuminate\Support\str::plural('comment',$thread->replies_count)}}</strong>
                                </div>
                                <div class="body">{{ $thread->body  }}</div>
                            </article>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
