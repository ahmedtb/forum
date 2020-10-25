@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @forelse($threads as $thread)

                    <div class="card mb-4">
                        <div class="card-header">

                            <div class="level">

                                <a href="{{ $thread->path() }}" class="flex"><h4>{{ $thread->title }}</h4></a>

                                <strong>{{$thread->replies_count}} {{Illuminate\Support\str::plural('comment',$thread->replies_count)}}</strong>
                            </div>


                        </div>

                        <div class="card-body">
                                <div class="body">{{ $thread->body  }}</div>
                        </div>
                    </div>
                @empty
                    <p>there is no records at this time. </p>
                @endforelse

            </div>
        </div>
    </div>
@endsection
