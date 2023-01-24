<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserRepository implements UserInterface 
{
    public function getInfoById($id) 
    {
        return User::findOrFail($id);
    }

    public function updateInfo($id, array $details) 
    {
        return User::whereId($id)->update($details);
    }
}