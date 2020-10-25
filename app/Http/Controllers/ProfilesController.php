<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    //
    public function show(User $user)
    {
        //        return $activities;
//        return $user->activity()->with('subject');
//        dd ($user);
        return view('profiles.show',[
            'profileUser' => $user,
            'threads' => $user->threads()->paginate(25),
            'activities' => $this->getActivity($user)
        ]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getActivity(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $user->activity()->latest()->with('subject')->get()->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });
    }
}
