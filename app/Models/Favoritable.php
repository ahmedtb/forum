<?php

namespace App\Models;

trait Favoritable {

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->user()->id)->count();
    }

    public function favorite($userId)
    {
        $attributes = ['user_id' => $userId];
        if (!$this->favorites()->where($attributes)->exists())
            return $this->favorites()->create($attributes);
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }
}
