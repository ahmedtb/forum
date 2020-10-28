<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Reply;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $reply->favorite(auth()->user()->id);
//        return back();
//         \DB::table('favorites')->insert([
//            'user_id' => auth()->user()->id,
//            'favorited_id' => $reply->id,
//            'favorited_type' => get_class($reply)
//        ]);
    }


        public function destroy(Reply $reply)
    {
        $reply->unfavorite();
    }
}
