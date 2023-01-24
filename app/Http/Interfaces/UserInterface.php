<?php

namespace App\Http\Interfaces;

interface UserInterface 
{
    public function getInfoById($id);
    public function updateInfo($id, array $details);
}