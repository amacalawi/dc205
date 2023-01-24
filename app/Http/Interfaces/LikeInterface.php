<?php

namespace App\Http\Interfaces;

interface LikeInterface 
{
    public function getCount($breed);
    public function isLiked($breed);
    public function peopleLiked($breed);
    public function findUser($user);
    public function createLike(array $details);
    public function updateLike($orderId, array $details);
}