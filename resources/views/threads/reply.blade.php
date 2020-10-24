<div class="card">


    <div class="card-header">

        <div class="level">
            <h5 class="flex">
        <a href="{{route('profile',$thread->creator->name)}}">
            {{$reply->owner->name}} </a>
        said {{ $reply->created_at->diffForHumans() }}....
            </h5>

            <div>
                <form action="/Replies/{{$reply->id}}/favorites" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" {{(!auth()->check() || $reply->isFavorited()) ? 'disabled' : ''}}>
                        {{$reply->favorites_count}} {{Illuminate\Support\Str::plural('favorite', $reply->favorites_count)}}
                    </button>
                </form>
            </div>

        </div>
    </div>


    <div class="card-body">
        {{ $reply->body }}
    </div>

</div>
