<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemAdmin extends Model
{
    use HasFactory;

    protected $table = 'users';
    public $timestamps = false;
    
    public static function login($username, $password)
    {
        $user = SystemAdmin::where('username', $username)->where('identity','=', '99')->first();
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        } else {
            return false;
        }
    }

}