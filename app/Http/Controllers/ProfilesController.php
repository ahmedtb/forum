<?php

namespace App\Http\Controllers;

use App\Models\Activity;
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
            'activities' => Activity::feed($user)
        ]);
    }
}
