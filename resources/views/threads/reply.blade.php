<reply :attributes="{{ $reply }}" inline-template v-cloak>

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
                    <button type="submit"
                            class="btn btn-primary" {{(!auth()->check() || $reply->isFavorited()) ? 'disabled' : ''}}>
                        {{$reply->favorites_count}} {{Illuminate\Support\Str::plural('favorite', $reply->favorites_count)}}
                    </button>
                </form>
            </div>


        </div>
    </div>


    <div class="card-body" id="reply-{{ $reply->id }}">
        <div v-if="editing">
            <div class="form-group">
                <textarea class="form-control" v-model="body"></textarea>

                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>

            </div>
        </div>

        <div v-else v-text="body"></div>

    </div>

    @can ('update', $reply)
        <div class="panel-footer level">

            <button class="btn btn-primary mr-1" @click="editing = true">Edit</button>
            <button class="btn btn-xs btn-danger mr-1" @click="destroy">Delete</button>


            {{--            <form method="POST" action="/replies/{{ $reply->id }}">--}}
{{--                {{ csrf_field() }}--}}
{{--                {{ method_field('DELETE') }}--}}

{{--                <button type="submit" class="btn btn-danger btn-xs">Delete</button>--}}
{{--            </form>--}}
        </div>
    @endcan

</div>

</reply>
