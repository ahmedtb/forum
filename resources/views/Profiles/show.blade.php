@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="page-header">

            <h1>
                {{$profileUser->name}}
                <small> since {{$profileUser->created_at->diffForHumans()}}</small>
            </h1>

        </div>

        @foreach($threads as $thread)
            <div class="card">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                    <a href="{{route('profile',$thread->creator->name)}}"> {{$thread->creator->name }} </a> posted:
                    {{ $thread->title }}
                        </span>

                        <span> since {{$thread->created_at->diffForHumans()}}</span>
                    </div>
                </div>

                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>
        @endforeach

        {{$threads->links()}}

    </div>


@endsection
