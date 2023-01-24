<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class UserController extends Controller
{
    private UserInterface $userRepository;
    private $carbon;

    public function __construct(UserInterface $userRepository, Carbon $carbon) 
    {   
        $this->userRepository = $userRepository;
        $this->middleware('auth:api');
        date_default_timezone_set('Asia/Manila');
        $this->carbon = $carbon;
    }

    public function find(Request $request, $id): JsonResponse 
    {
        return response()->json([
            'data' => $this->userRepository->getInfoById($id)
        ]);
    }

    public function update(Request $request, $id): JsonResponse 
    {
        $details = $request->only([
            'name',
            'email',
            'gender',
            'birthdate',
            'contact_no',
            'address'
        ]);
        
        return response()->json([
            'status' => 'success',
            'data' => $this->userRepository->updateInfo($id, $details),
            'message' => 'User updated successfully.',
        ]);
    }
}
