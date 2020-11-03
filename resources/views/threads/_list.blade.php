@forelse($threads as $thread)

<div class="card mb-4">
    <div class="card-header">

        <div class="level">
            <div class="flex">
                <h4>
                    <a href="{{ $thread->path() }}" class="flex">

                        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                        <strong>
                            {{ $thread->title }}
                        </strong>
                        @else
                        {{ $thread->title }}
                        @endif

                    </a>
                </h4>

                <h5>
                    Posted By: <a
                        href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                </h5>
            </div>

            <strong>{{$thread->replies_count}} {{Illuminate\Support\str::plural('comment',$thread->replies_count)}}</strong>
        </div>


    </div>

    <div class="card-body">
        <div class="body">{{ $thread->body  }}</div>
    </div>


    <div class="card-footer">
        {{ $thread->visits }} Visits
    </div>

</div>
@empty
<p>there is no records at this time. </p>
@endforelse
