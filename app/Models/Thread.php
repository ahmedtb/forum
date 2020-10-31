<?php

namespace App\Models;

use App\Filters\ThreadFilters;
use App\Providers\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;
    use RecordActivity;

    protected $guarded = [];

    protected $with = ['creator','channel'];
    protected $appends = ['isSubscribedTo'];

    public static function boot()
    {
        Parent::boot();


        static::deleting(function ($thread){
            $thread->replies->each->delete();
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {

        $reply = $this->replies()->create($reply);

<<<<<<< HEAD
        $this->notifySubscriber($reply);

//        event(new ThreadHasNewReply($this,$reply));
=======
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify( $reply);

>>>>>>> refs/remotes/origin/master

        return $reply;
    }

    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    /**
     * @param Model $reply
     */
    protected function notifySubscriber(Model $reply): void
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }
}
