<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Device extends Model
{
    use HasFactory;
    
    public static function search($userid)
    {    
        $query = DB::table('machine')
                ->select(
                    'machine.machine_mac as mac',
                    'machine.id as id',
                    'machine.name as name',
                )
                ->where('user_id','=',$userid);
        $results =  $query->get(); 

        return $results;
    }

    public static function revise_device($device_id, $device_name, $device_number)
    {    
        $error_message = null;
        $device  = DB::table('machine') //找看看有沒有被註冊過
                    ->select(
                        'name',
                        'machine_mac'
                    )
                    ->where('machine_mac','=',$device_number)
                    ->first();
        if (empty($device) || $device->name != $device_name) 
        {
            $checke_example =DB::table('machine_example') //找有沒有這個序號
                            ->select(
                                'id',
                                'machine_mac',
                                'mac',
                                'anydesk_ssid',
                                'anydesk_psk',
                            )
                            ->where('machine_mac','=',$device_number)
                            ->first();
            
            if($checke_example){
                DB::table('machine')
                        ->where('id', '=', $device_id)
                        ->update([
                            'name' => $device_name,
                            'machine_mac' => $device_number,
                            'mac' => $checke_example->mac,
                            'anydesk_ssid' => $checke_example->anydesk_ssid,
                            'anydesk_psk' => $checke_example->anydesk_psk,
                        ]);
            }else{
                $error_message = 2;
            }
        }else{
            $error_message = 1;
        }
        return $error_message;
    }

    public static function add_device($device_name, $device_number)
    {    
        $error_message = null;
        $device  = DB::table('machine') //找看看有沒有被註冊過
                    ->select(
                        'id',
                    )
                    ->where('machine_mac','=',$device_number)
                    ->first();
        if (empty($device)) 
        {
            $checke_example =DB::table('machine_example') //找有沒有這個序號
                            ->select(
                                'id',
                                'machine_mac',
                                'mac',
                                'anydesk_ssid',
                                'anydesk_psk',
                            )
                            ->where('machine_mac','=',$device_number)
                            ->first();
            if($checke_example){
                DB::table('machine')
                        ->insert([
                            'user_id' => session('admin_user.id'),
                            'name' => $device_name,
                            'machine_mac' => $device_number,
                            'mac' => $checke_example->mac,
                            'anydesk_ssid' => $checke_example->anydesk_ssid,
                            'anydesk_psk' => $checke_example->anydesk_psk,
                            'add_date' => date('Y-m-d H:i:s'),
                            'update_date' => date('Y-m-d H:i:s'),
                        ]);
            }else{
                $error_message = 2;
            }
        }else{
            $error_message = 1;
        }
        return $error_message;
    }
    
}
