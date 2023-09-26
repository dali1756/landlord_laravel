<?php

namespace App\Models;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Admin extends Model
{
    use HasFactory;

    protected $table = 'users';
    public $timestamps = false;
    
    public static function login($username, $password)
    {
        $user = Admin::where('username', $username)->first();
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        } else {
            return false;
        }
    }

    public static function updatePassword($username, $oldPwd, $newPwd)
    {
        $user = Admin::where('username', $username)->first();
        if ($user && Hash::check($oldPwd, $user->password)) {
            $newPassword = Hash::make($newPwd);
            Admin::where('username', $username)->update(['password' => $newPassword]);
            return true;
        } else {
            return false;
        }
    }

    public static function register_search($username)
    {
        $user = Admin::where('username', $username)->first();

        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
    public static function machine_search($machine)
    {
        $error_message = null;
        $machine_search = DB::table('machine_example')
            ->where('machine_mac', '=', $machine)
            ->get();

        if ($machine_search->isEmpty()) {
            $error_message = 1;
            // 返回第一种错误消息
        }else{
            $query = DB::table('machine_example')
                    ->whereNotIn('mac', function ($query) {
                        $query->select('mac')->from('machine');
                    })
                    ->where('machine_mac', '=', $machine);
            $machine_search = $query ->get();
            if ($machine_search->isEmpty()) {
                $error_message = 2;
            } else {
                return $machine_search;
            }
        }
        return $error_message;
    }
    public static function machine_insert($username, $cname, $password, $email, $machine_mac)
    {

        $insertedId = DB::table('users')->insertGetId([
                'username' => $username,
                'cname' => $cname,
                'password' => Hash::make($password),
                'email' => $email,
                'add_date' => date('Y-m-d H:i:s'),
                'update_date' => date('Y-m-d H:i:s'),
                'identity' => 1,
            ]);//新增帳號

        foreach($machine_mac as $i => $row){
            DB::table('machine')->insert([
                'user_id' => $insertedId,
                'name' => $cname.'裝置'.$i,
                'machine_mac' => $row->machine_mac,
                'mac' => $row->mac,
                'anydesk_ssid' => $row->anydesk_ssid,
                'anydesk_psk' => $row->anydesk_psk,
                'add_date' => date('Y-m-d H:i:s'),
                'update_date' => date('Y-m-d H:i:s'),
            ]);//新增機器
        }
        
        if (!empty($insertedId)) {
            return true;
        } else {
            return false;
        }
    }
    
}