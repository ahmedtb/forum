@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/jquery.atwho.css">
@endsection

@section('content')

    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">

                            <img src="{{ $thread->creator->avatar_path }}"
                                 alt="{{ $thread->creator->name }}"
                                 width="25"
                                 height="25"
                                 class="mr-1">


                            <div class="level">
                            <span class="flex">
                        <a href="{{route('profile',$thread->creator->name)}}"> {{$thread->creator->name }} </a> posted:
                        {{ $thread->title }}
                            </span>
                                @can('update',$thread)
                                    <form action="{{$thread->path()}}" method="POST">
                                        @csrf
                                        {{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-primary">Delete</button>
                                    </form>
                                @endcan

                            </div>
                        </div>

                        <div class="card-body">
                            {{ $thread->body }}
                        </div>
                    </div>
                </div>
                <div class="col-md-8">


                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>

                </div>

{{--                <div class="col-md-8">{{$replies->links()}}</div>--}}




            </div>


            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p>this post was created at {{$thread->created_at->diffForHumans()}} ago by:
                            <a href="#">{{$thread->creator->name}}</a> and currently has
                            <span v-text="repliesCount"></span> {{Illuminate\Support\str::plural('comment',$thread->replies_count)}}
                            ..
                        </p>

                        <p>
                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                        </p>
                    </div>
                </div>
            </div>

        </div>


    </thread-view>

@endsection
