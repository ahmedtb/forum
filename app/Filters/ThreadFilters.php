<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];


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
}
