<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function create(array $data) : array
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data)->format();
    }

}