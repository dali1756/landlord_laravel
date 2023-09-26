<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;


class DeviceController extends Controller
{
    //
    public function index()
    {
        $device = Device::search(session('admin_user.id'));
        $data = [
            'device' => $device,
        ];
        return view('device', $data);
    }


    public function revise_device(Request $request)
    {
        $device_id = $request->input('device_id');
        $device_name = $request->input('device_name');
        $device_number = $request->input('device_number');
        $hidden_id = $request->input('hidden_id');
        $error =[];
        if(!empty($device_id)){
            foreach ($device_id as $i => $deviceId) {
                if(empty($device_name[$i]) || empty($device_number[$i])){
                    $error[] ="ID:".($hidden_id[$i])." 名稱或序號不可填空";
                }else{
                    $revise_device = Device::revise_device($deviceId, $device_name[$i], $device_number[$i]);
                    if ($revise_device == 1) { 
                        $error[] ="ID:".($hidden_id[$i])." 序號已被註冊！！";
                    }elseif($revise_device == 2) {
                        $error[] ="ID:".($hidden_id[$i])." 序號不存在，請重新輸入序號！！";
                    }else{
                        $error[] ="ID:".($hidden_id[$i])." 成功設定！！";
                    }
                }
            }
        }else{
            $error[] ="請勾選要變更的資料！";
        }
        
        return back()->withErrors($error);
    }

    public function add_device(Request $request)
    {
        $device_name = $request->input('device_name');
        $device_number = $request->input('device_number');
        $add_device = Device::add_device($device_name, $device_number);
        $error =[];
        if ($add_device == 1) { 
            $error[] ="序號已被註冊！！";
        }elseif($add_device == 2) {
            $error[] ="序號不存在，請重新輸入序號！！";
        }
        return back()->withErrors($error);
    }
}
