<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Power extends Model
{
    use HasFactory;
    
    public static function dong($userid)
    {
        $results = DB::table('machine')
        ->leftJoin('room AS r', 'r.center_id', '=', 'machine.id')
        ->where('userid', '=', $userid)->get();
        
        return $results;
    } 
    public static function update_dong($userid)
    {
        $roomQuery = DB::table('room')
            ->select('id', 'name', 'userid')
            ->where('userid', '=', $userid)
            ->groupBy('id','name', 'userid');

        $roomData = $roomQuery->get();
        foreach ($roomData as $room) {
            $room_id = $room->id;
            
            $roomAmountQuery = DB::table('room_amount_log AS res')
                ->select(
                    'res.id as id',
                    'res.amount as amount',
                    'res.amount_220 as amount_220',
                )
                ->leftJoin('room AS r', 'r.id', '=', 'res.room_id')
                ->where('res.room_id', $room_id)
                ->orderBy('res.add_date', 'ASC')
                ->orderBy('res.room_id', 'ASC');

            $roomAmountData = $roomAmountQuery->get();
            
            $temp = 0;
            $temp2 = 0;
            
            foreach ($roomAmountData as $k => $val) {
                if ($k == 0) {
                    $diff = $val->amount - $temp;
                    $diff_220 = $val->amount_220 - $temp2;
                    
                    $updated = DB::table('room_amount_log')
                        ->where('id', $val->id)
                        ->update([
                            'amount_use' => $diff,
                            'amount_use_220' => $diff_220,
                            'update_date' => now()
                        ]);
                    
                    $temp = $val->amount;
                    $temp2 = $val->amount_220;
                }
                
                if ($val->amount != 0 && $val->amount_220 != 0) {
                    $diff = $val->amount - $temp;
                    $diff_220 = $val->amount_220 - $temp2;
                    
                    $temp = $val->amount;
                    $temp2 = $val->amount_220;
                    
                    if ($k != 0) {
                        $updated = DB::table('room_amount_log')
                            ->where('id', $val->id)
                            ->update([
                                'amount_use' => $diff,
                                'amount_use_220' => $diff_220,
                                'update_date' => now()
                            ]);
                    }
                }
            }
        }
    } 
    public static function power_record($roomid, $start_date, $end_date)
    {
        // 建立基本查詢
        $query = DB::table('room_amount_log AS res')
            ->select(
                'res.room_id AS room_id',
                DB::raw('DATE(res.add_date) AS add_date'),
                'r.name',
                DB::raw('ROUND(SUM(res.amount_use), 2) AS daily_total_amount'),
                DB::raw('ROUND(AVG(res.price_degree), 1) AS price_degree_avg'),
                DB::raw('ROUND(SUM(res.amount_use) * ROUND(AVG(res.price_degree), 1) , 2) AS total110_money'), // 使用 DB::raw() 計算 total110_money
                DB::raw('ROUND(SUM(res.amount_use_220), 2) AS daily_total_amount_220'),
                DB::raw('ROUND(AVG(res.price_degree_220), 1) AS price_degree_avg_220'),
                DB::raw('ROUND(SUM(res.amount_use_220) * ROUND(AVG(res.price_degree_220), 1) , 2) AS total220_money'), // 使用 DB::raw() 計算 total220_money
                                
            )
            ->leftJoin('room AS r', 'r.id', '=', 'res.room_id')
            ->where('r.userid', '=', session('admin_user.id'));

        if (!empty($start_date)) {
            $query->where('res.add_date','>',$start_date);
        }

        if (!empty($end_date)) {
            $query->where('res.add_date','<',$end_date);
        }

        if (!empty($roomid)) {
            $query->where('room_id', '=', $roomid);
        }
        // 加入 GROUP BY 和 ORDER BY 子句，這裡加入 'r.name'
        $query->groupBy('r.name', DB::raw('DATE(res.add_date), res.room_id'))
            ->orderByDesc(DB::raw('DATE(res.add_date)'))
            ->orderBy('res.room_id');

        // 執行查詢並取得結果
        $results = $query->get();
        
        if ($results->isEmpty()) {
            return false;
        }else{
            $results = $query;
        }
        
        return $results;
    } 

    public static function power_record_list($start_date, $end_date, $roomid)
    {

        $query =  DB::table('room_amount_log AS res')
            ->select(
                'res.room_id as room_id',
                DB::raw('ROUND(res.amount_use, 2) as amount'),
                'res.price_degree as price_degree',
                DB::raw('ROUND(res.amount_use_220, 2) as amount_220'),
                'res.price_degree_220 as price_degree_220',
                'res.add_date as add_date',
                DB::raw('ROUND((res.amount_use * res.price_degree) , 2) AS total110_money'), // 使用 DB::raw() 計算 total110_money
                DB::raw('ROUND((res.amount_use_220 * res.price_degree_220) , 2) AS total220_money'), // 使用 DB::raw() 計算 
                'r.name'
            )
            ->leftJoin('room AS r', 'r.id', '=', 'res.room_id')
            ->where('r.userid', '=', session('admin_user.id'));

        if (!empty($start_date)) {
            $query->where('res.add_date', '>', $start_date);
        }

        if (!empty($end_date)) {
            $query->where('res.add_date', '<', $end_date);
        }
        if (!empty($roomid)) {
            $query->where('room_id', '=', $roomid);
        }

        $query->orderBy('res.add_date', 'asc')
            ->orderBy('res.room_id', 'asc');

        $results = $query->get();
        return $results;
    }

    public static function power_nowmeter($room_id)
    {
        $query = DB::table('room')
            ->select(
                'name',
                'amount',
                'amount_220',
            )
            ->where('userid', '=', session('admin_user.id'));
        if (!empty($room_id)) {
            $query->where('id','=', $room_id);
        }
        $results = $query->get();

        if ($results->isEmpty()) {
            return false;
        }

        return $results;
    }

    public static function power_consumption_m($room_id, $date)
    {
          
        $query = DB::table('room_amount_log')
            ->select(
                'room_id',
                DB::raw('round(SUM(amount_use) , 2) as monthly_amount'),
                DB::raw('round(SUM(amount_use_220) ,2) as monthly_amount_220'),
                'r.name' // 在這裡加入 'r.name'
            )
            ->leftJoin('room AS r', 'r.id', '=', 'room_id')
            ->where(DB::raw("DATE_FORMAT(room_amount_log.add_date, '%Y-%m')"), '=', $date)
            ->where('r.userid', '=', session('admin_user.id'));
        if (!empty($room_id)) {
            $query->where('r.id','=', $room_id);
        }
        $query->groupBy('room_id', 'r.name')
              
              ->orderBy('room_id');
        $results = $query->get();
        
        if ($results->isEmpty()) {
            return false;
        }

        return $results;
    }

    public static function power_consumption_d($room_id, $date)
    {
          
        $query = DB::table('room_amount_log')
            ->select(
                'r.name',// 在這裡加入 'r.name'
                DB::raw('round(SUM(amount_use) , 2) as monthly_amount'),
                DB::raw('round(SUM(amount_use_220) ,2) as monthly_amount_220'),
                'room_id'
            )
            ->leftJoin('room AS r', 'r.id', '=', 'room_id')
            ->where(DB::raw("DATE(room_amount_log.add_date)"), '=', $date)
            ->where('r.userid', '=', session('admin_user.id'));

        if (!empty($room_id)) {
            $query->where('r.id','=', $room_id);
        }
        $query->groupBy('room_id', 'r.name')
              ->orderBy('room_id');
        $results = $query->get();

        if ($results->isEmpty()) {
            return false;
        }

        return $results;
    }

    public static function rate_search($room_numbers)
    {
        $query = DB::table('room')
               ->where('id','=', $room_numbers)
               ->where('userid', '=', session('admin_user.id'));
        
        $results = $query->get();

        if ($results->isEmpty()) {
            return false;
        }

        return $results;
    }

    public static function rate_update($room_numbers, $price_elec_degree, $price_elec_degree_220)
    {
        $query = DB::table('room')
                ->where('id', '=', $room_numbers)
                ->where('userid', '=', session('admin_user.id'));

        $affectedRows = $query->update([
            'price_degree' =>  $price_elec_degree, 
            'price_degree_220' => $price_elec_degree_220, 
        ]);

        if ($affectedRows === 0) {
            return false;
        }

        return true;
    }

    // 開關電房號查詢
    public static function power_switch_update($room_id, $switch_power)
    {

        $query = DB::table('room')
            ->where('id', '=', $room_id)
            ->where('userid', '=', session('admin_user.id'));

        $affectedRows = $query->update([
            'mode' => $switch_power, 
        ]);

        if ($affectedRows === 0) {
            return false;
        }

       return true;
    
    }
}
