<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loglist extends Model
{
    use HasFactory;
    protected $table = 'log_list';
    
    public static function log($content, $data_type)
    {
        $data = [
            'content' => $content,
            'data_type' => $data_type,
            'add_date' => date('Y-m-d H:i:s'),
        ];

        Loglist::insert($data);
    }
}
