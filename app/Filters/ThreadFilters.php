<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{
    protected $filters = ['by','popular'];


    /**
     * @param $username
     * @param $builder
     * @return mixed
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        $this->builder->where('user_id', $user->id);
    }

    protected function popular(){

        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count','desc');
    }
}
