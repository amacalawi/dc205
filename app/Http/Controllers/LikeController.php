<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\UpdateLikeRequest;
use App\Http\Interfaces\LikeInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


class LikeController extends Controller
{   
    private LikeInterface $likeRepository;
    private $carbon;

    public function __construct(LikeInterface $likeRepository, Carbon $carbon) 
    {   
        $this->likeRepository = $likeRepository;
        $this->middleware('auth:api');
        date_default_timezone_set('Asia/Manila');
        $this->carbon = $carbon;
    }

    public function get_likes(Request $request): JsonResponse 
    {
        $breed = $request->route('breed');

        return response()
        ->json([
            'count' => $this->likeRepository->getCount($breed),
            'isLike' => $this->likeRepository->isLiked($breed),
            'people' => $this->likeRepository->peopleLiked($breed)
        ]);
    }

    public function toggle_like(Request $request): JsonResponse 
    {
        $breed = $request->breed;
        $res = $this->likeRepository->findUser(Auth::user()->id);

        if ($res !== 0) {
            if (!empty($res->likes)) {
                $likes = explode(',', $res->likes);
                if (in_array($breed, $likes)) {
                    $pos = array_search($breed, $likes);
                    if ($pos !== false) {
                        unset($likes[$pos]);
                    }
                    sort($likes);
                    $details = [
                        'likes' => implode(',', $likes),
                        'updated_at' => $this->carbon::now(),
                        'updated_by' => Auth::user()->id
                    ];
                    $this->likeRepository->updateLike($res->id, $details);
                    return response()
                    ->json([
                        'status' => 'ok',
                        'messages' => 'You have successfully unliked ('.$breed.').',
                        'data' => $likes,
                        'like' => false,
                        'count' => $this->likeRepository->getCount($breed),
                        'people' => $this->likeRepository->peopleLiked($breed)->map(function($a) { return '<img class="rounded-circle" id="'.$a->user->id.'" src="https://www.gravatar.com/avatar/' . md5(strtolower( trim( $a->user->email ) )).'" title="'.ucwords($a->user->name).'"/>'; })
                    ]);
                } else {
                    if (count($likes) == 3) {
                        return response()
                        ->json([
                            'status' => 'error',
                            'messages' => 'Oops sorry! you dont have enough like count.',
                            'data' => '',
                            'like' => false,
                            'count' => $this->likeRepository->getCount($breed),
                            'people' => $this->likeRepository->peopleLiked($breed)->map(function($a) { return '<img class="rounded-circle" id="'.$a->user->id.'" src="https://www.gravatar.com/avatar/' . md5(strtolower( trim( $a->user->email ) )).'" title="'.ucwords($a->user->name).'"/>'; })
                        ]);
                    } else {
                        $likes[] = $breed; 
                        sort($likes);
                        $details = [
                            'likes' => implode(',', $likes),
                            'updated_at' => $this->carbon::now(),
                            'updated_by' => Auth::user()->id
                        ];
                        $this->likeRepository->updateLike($res->id, $details);
                        return response()
                        ->json([
                            'status' => 'ok',
                            'messages' => 'You have successfully liked ('.$breed.').',
                            'data' => $likes,
                            'like' => true,
                            'count' => $this->likeRepository->getCount($breed),
                            'people' => $this->likeRepository->peopleLiked($breed)->map(function($a) { return '<img class="rounded-circle" id="'.$a->user->id.'" src="https://www.gravatar.com/avatar/' . md5(strtolower( trim( $a->user->email ) )).'" title="'.ucwords($a->user->name).'"/>'; })
                        ]);
                    }
                }
            }
            $details = [
                'likes' => $breed,
                'updated_at' => $this->carbon::now(),
                'updated_by' => Auth::user()->id
            ];
            $this->likeRepository->updateLike($res->id, $details);
            return response()
            ->json([
                'status' => 'ok',
                'messages' => 'You have successfully liked ('.$breed.').',
                'data' => $breed,
                'like' => true,
                'count' => $this->likeRepository->getCount($breed),
                'people' => $this->likeRepository->peopleLiked($breed)->map(function($a) { return '<img class="rounded-circle" id="'.$a->user->id.'" src="https://www.gravatar.com/avatar/' . md5(strtolower( trim( $a->user->email ) )).'" title="'.ucwords($a->user->name).'"/>'; })
            ]);
        }

        $details = [
            'likes' => $breed,
            'created_at' => $this->carbon::now(),
            'created_by' => Auth::user()->id
        ];
        $this->likeRepository->createLike($details);
        return response()
        ->json([
            'status' => 'ok',
            'messages' => 'You have successfully liked ('.$breed.').',
            'data' => $breed,
            'like' => true,
            'count' => $this->likeRepository->getCount($breed),
            'people' => $this->likeRepository->peopleLiked($breed)->map(function($a) { return '<img class="rounded-circle" id="'.$a->user->id.'" src="https://www.gravatar.com/avatar/' . md5(strtolower( trim( $a->user->email ) )).'" title="'.ucwords($a->user->name).'"/>'; })
        ]);
    }
}
