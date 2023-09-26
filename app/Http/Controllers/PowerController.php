<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Power;
use App\Models\Loglist;

class PowerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    // 私有方法：取得電力使用資料
    private function getPowerData()
    {
        return Power::dong(session('admin_user.id'));
    }

    // 查詢電力使用紀錄
    public function power_record(Request $request)
    {
        $room = $this->getPowerData();
        $room_id = $request->input('room_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $_token = $request->input('_token');
        $data = [
            'room' => $room,
            'room_id' => $room_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'error' => null,
        ];
        
        $result = Power::power_record($room_id, $start_date, $end_date);
        if ($request->session()->token() == $request->input('_token')) {
        if($result){
            $result = $result->paginate(10)->withQueryString();
            $data['result'] = $result->appends(['room_id' => $room_id, 'start_date' => $start_date, 'end_date' => $end_date, '_token' => $_token]);
        }else{
            $data['error'] = '查無資料'; 
        }
        }
        return view('power-record',$data);
    }

   
    // 查詢用電現況
    public function power_nowmeter(Request $request)
    {
        $room = $this->getPowerData();
        $room_id = $request->input('room_id');
        
        if($room_id == 'all') $room_id = null;
        $data = [
            'room' => $room,
            'room_id' => $room_id,
            'error' => null,
        ];
        if ($request->session()->token() == $request->input('_token')) {
            $result = Power::power_nowmeter($room_id);
            if($result){
                $data['result'] = $result;
            }else{
                $data['error'] = '查無資料'; 
            }
        }
        return view('power-nowmeter', $data);
    }

    // 查詢電量統計-天
    public function power_consumption_d(Request $request)
    {
        $room = $this->getPowerData();
        $room_id = $request->input('room_id');
        $day = $request->input('day');

        $data = [
            'room' => $room,
            'room_id' => $room_id,
            'day' => $day,
            'error' => null,
        ];
        if ($request->session()->token() == $request->input('_token')) {
            $result = Power::power_consumption_d($room_id, $day);
            if ($result !== false) {
                $monthly_total_amount = $result->sum('monthly_amount');
                $monthly_total_amount_220 = $result->sum('monthly_amount_220');
                $monthly_total = $monthly_total_amount + $monthly_total_amount_220;
                // 添加其他数据到 $data 中
                $data['result'] = $result;
                $data['monthly_total_amount'] = $monthly_total_amount;
                $data['monthly_total_amount_220'] = $monthly_total_amount_220;
                $data['monthly_total'] = $monthly_total;
                $data['startdate']   =   $day . ' 00:00:00';
                $data['enddate']     =   $day . ' 23:59:59';
            }else{
                $data['error'] = '查無資料'; 
            }
        }

        return view('power-consumption-d', $data);
    }

    // 查詢電量統計-月
    public function power_consumption_m(Request $request)
    {
        $room = $this->getPowerData();
        $room_id = $request->input('room_id');
        $year = $request->input('year');
        $month = $request->input('month');

        $data = [
            'room' => $room,
            'room_id' => $room_id,
            'year' => $year,
            'month' => $month,
            'error' => null,
        ];
        if ($request->session()->token() == $request->input('_token')) {         
            $date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT); 
            $result = Power::power_consumption_m($room_id,$date);
            if($result){
                $monthly_total_amount = $result->sum('monthly_amount');
                $monthly_total_amount_220 = $result->sum('monthly_amount_220');
                $monthly_total = $monthly_total_amount + $monthly_total_amount_220;

                // 添加其他数据到 $data 中
                $data['result'] = $result;
                $data['monthly_total_amount'] = $monthly_total_amount;
                $data['monthly_total_amount_220'] = $monthly_total_amount_220;
                $data['monthly_total'] = $monthly_total;
                $data['enddate']   = $date;
                $data['startdate'] = $date; 
            }else{
                $data['error'] = '查無資料'; 
            }
        }
        
        return view('power-consumption-m', $data);
    }

    // 費率設定
    public function rate_search(Request $request)
    {
        $room = $this->getPowerData();
        $room_id = $request->input('room_id');

        $data = [
            'room' => $room,
            'room_id' => $room_id,
            'error' => null,
        ];
        if(!empty($room_id)){
            $result = Power::rate_search($room_id);
            if ($result !== false) {
                $data['result'] = $result;
                $data['price_degree'] = $result->first()->price_degree;
                $data['price_degree_220'] = $result->first()->price_degree_220;
                $data['price_degree_220'] = $result->first()->price_degree_220;
            }else{
                $data['error'] = '查無資料'; 
            } 
        }
        
        return view('rate', $data);
    }

    // 費率設定更新
    public function rate_update(Request $request)
    {
        $room = $this->getPowerData();
        $price_elec_degree = $request->input('price_elec_degree');//新110V費率
        $price_elec_degree_220 = $request->input('price_elec_degree_220');//新220V費率

        $old_price_elec_degree = $request->input('old_price_elec_degree');//舊110V費率
        $old_price_elec_degree_220 = $request->input('old_price_elec_degree_220');//舊220V費率

        $room_numbers_hidden = $request->input('room_numbers_hidden');

        Power::rate_update($room_numbers_hidden, $price_elec_degree, $price_elec_degree_220);
        if($old_price_elec_degree != $price_elec_degree){
            $content = "帳號id:".session('admin_user.id')."；".session('admin_user.username')."  110V電錶費率修改；原費率：".$old_price_elec_degree."；新費率：".$price_elec_degree;
            Loglist::log($content, session('admin_user.identity'));
        }
        if($old_price_elec_degree_220 != $price_elec_degree_220){
            $content = "帳號id:".session('admin_user.id')."；".session('admin_user.username')."  220V電錶費率修改；原費率：".$old_price_elec_degree_220."；新費率：".$price_elec_degree_220;
            Loglist::log($content, session('admin_user.identity'));
        }
        $data = [
            'room' => $room,
            'room_id' => $room_numbers_hidden,
            'error' => null,
        ];
        $result = Power::rate_search($room_numbers_hidden);
        $data['result'] = $result;
        $data['price_degree'] = $result->first()->price_degree;
        $data['price_degree_220'] = $result->first()->price_degree_220;
        
        return view('rate', $data);
    }

    // 開關電房號查詢
    public function power_switch_search(Request $request)
    {
        $room = $this->getPowerData();
        $room_id = $request->input('room_id');
        $data = [
            'room' => $room,
            'room_id' => $room_id,
            'error' => null,
        ];
        if(!empty($room_id)){
            $result = Power::rate_search($room_id);
            if ($result !== false) {
                $data['result'] = $result;
                $data['roomid'] = $result->first()->id;
                $data['mode'] = $result->first()->mode;
            }else{
                $data['error'] = '查無資料'; 
            }
        }

        return view('power-switch', $data);
    }

    // 開關電房號更新
    public function power_switch_update(Request $request)
    {
        $room_numbers = $request->input('room_numbers');
        $data = [
            'room_numbers' => $room_numbers,
            'error' => null,
        ];
        $room_id = $request->input('room_id');
        $switch_power = $request->input('switch_power');
        $result = Power::power_switch_update($room_id, $switch_power);

        if ($result !== false) {
            $data['result'] = $result;
            $data['roomid'] = $room_id;
            $data['mode'] = $switch_power;
            if($switch_power == 1){
                $content = "帳號id:".session('admin_user.id')."；".session('admin_user.username')."開電";
                Loglist::log($content, session('admin_user.identity')); 
            }elseif($switch_power == 4){
                $content = "帳號id:".session('admin_user.id')."；".session('admin_user.username')."關電";
                Loglist::log($content, session('admin_user.identity')); 
            }
        }

        return response()->json(['success' => true, 'message' => '開/關電成功', 'mode' => $switch_power]);
    }
}
