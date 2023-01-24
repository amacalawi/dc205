<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\LikeInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;

class LikeRepository implements LikeInterface 
{
    public function getCount($breed) 
    {
        return Like::where('likes', 'like', '%' . $breed . '%')->count();
    }

    public function isLiked($breed) 
    {
        return Like::where('likes', 'like', '%' . $breed . '%')->where('created_by', Auth::user()->id)->count();
    }

    public function peopleLiked($breed) 
    {
        return Like::with([
            'user' =>  function($q) { 
                $q->select(['id', 'name', 'email']);
            }
        ])->where('likes', 'like', '%' . $breed . '%')->get();
    }

    public function findUser($user)
    {
        $res = Like::where('created_by', $user)->get();
        if ($res->count() > 0) {
            return $res->first();
        }
        return 0;
    }

    public function createLike(array $details) 
    {
        return Like::create($details);
    }

    public function updateLike($id, array $details) 
    {
        return Like::whereId($id)->update($details);
    }
}