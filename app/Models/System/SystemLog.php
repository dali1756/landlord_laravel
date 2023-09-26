<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemLog extends Model
{
    use HasFactory;
    protected $table = 'log_list';
    
    public static function index()
    {
        $log = SystemLog::all();
        return $log;
    }

}