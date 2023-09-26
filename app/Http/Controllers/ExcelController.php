<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Power;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ExcelController extends Controller
{
    // 私有方法：取得電力使用資料
    private function getPowerData()
    {
        return Power::dong(session('admin_user.id'));
    }

    // 電力使用紀錄匯出
    public function power_record_excel(Request $request)
    {
        $room = $this->getPowerData();
        $room_id = $request->input('room_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $result = Power::power_record($room_id, $start_date, $end_date)->get();
        
        $exportData = new class($result) implements FromCollection, WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
                $this->data->transform(function ($item, $index) {
                    $item->room_id = $index + 1;//將搜尋出來的room_id改為流水號
                    $item->daily_total_amount = $item->daily_total_amount == 0 ? '0' : $item->daily_total_amount; // 將數字 0 轉為字符串 '0'
                    $item->daily_total_amount_220 = $item->daily_total_amount_220 == 0 ? '0' : $item->daily_total_amount_220; // 將數字 0 轉為字符串 '0'
                    $item->total110_money = $item->total110_money == 0 ? '0' : $item->total110_money; // 將數字 0 轉為字符串 '0'
                    $item->total220_money = $item->total220_money == 0 ? '0' : $item->total220_money; // 將數字 0 轉為字符串 '0'
                    
                    return $item;
                });
            }

            public function collection()
            {
                return $this->data;
            }
            public function headings(): array
            {
                return [
                    '#',
                    '日期',
                    '房號',
                    '用電度數(110V)',
                    '費率(110V)',
                    '金額(110V)',
                    '用電度數(220V)',
                    '費率(220V)',
                    '金額(220V)',
                ];
            }
        };
        
        return Excel::download($exportData, 'power_record.xlsx');
    }

    // 用電現況匯出
    public function power_nowmeter_excel(Request $request)
    {
        $room = $this->getPowerData();
        $room_id = $request->input('room_id');
        $result = Power::power_nowmeter($room_id);
        
        $exportData = new class($result) implements FromCollection, WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function collection()
            {
                return $this->data;
            }
            public function headings(): array
            {
                return [
                    '房號',
                    '目前電表度數(110V)',
                    '目前電表度數(220V)',
                ];
            }
        };
        
        return Excel::download($exportData, 'power_nowmeter.xlsx');
    }

    // 電量統計匯出-日
    public function power_consumption_d_excel(Request $request)
    {
        $room = $this->getPowerData();
        $room_id = $request->input('room_id');
        $day = $request->input('day');
        $result = Power::power_consumption_d($room_id, $day);
        // 初始化总计变量
        $total_110 = 0;
        $total_220 = 0;
        $exportData = new class($result, $day, $total_110, $total_220) implements FromCollection, WithHeadings, WithColumnWidths {
            private $data;
            private $day;
            private $total_110;
            private $total_220;

            public function __construct($data, $day, $total_110, $total_220)
            {
                $this->data = $data;
                $this->day = $day;
                $this->total_110 = $total_110;
                $this->total_220 = $total_220;
            }

            public function collection()
            {
                $filteredData = $this->data->map(function ($item) {
                    $item->startdate = $this->day . ' 00:00:00';
                    $item->enddate = $this->day . ' 23:59:59';
                    $item->monthly_total = $item->monthly_amount + $item->monthly_amount_220;
                    $this->total_110 += $item->monthly_amount;
                    $this->total_220 += $item->monthly_amount_220;
                    return [
                        '房號' => $item->name,
                        '開始年月' => $item->startdate,
                        '結束年月' => $item->enddate,
                        '用電總計(110V)' => $item->monthly_amount,
                        '用電總計(220V)' => $item->monthly_amount_220,
                        '用電總計' => $item->monthly_total,
                        
                    ];
                });
                $filteredData->push([
                    '房號' => '總計',
                    '開始年月' => '',
                    '結束年月' => '',
                    '用電總計(110V)' => $this->total_110,
                    '用電總計(220V)' => $this->total_220,
                    '用電總計' => $this->total_110 + $this->total_220,
                ]);
                return $filteredData;
            }
            public function headings(): array
            {
                return [
                    '房號',
                    '開始年月',
                    '結束年月',
                    '用電總計(110V)',
                    '用電總計(220V)',
                    '用電總計',
                ];
            }
            public function columnWidths(): array
            {
                return [
                    'A' => 10, // 房號欄位寬度為 10
                    'B' => 20, // 開始年月欄位寬度為 20
                    'C' => 20, // 結束年月欄位寬度為 20
                    'D' => 15, // 用電總計(110V)欄位寬度為 15
                    'E' => 15, // 用電總計(220V)欄位寬度為 15
                    'F' => 10, // 用電總計欄位寬度為 10
                ];
            }
        };
        
        return Excel::download($exportData, 'power_consumption_d.xlsx');
    }

    // 電量統計匯出-月
    public function power_consumption_m_excel(Request $request)
    {
        $room = $this->getPowerData();
        $room_id = $request->input('room_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT); 
        $result = Power::power_consumption_m($room_id, $date);
        
        // 初始化总计变量
        $total_110 = 0;
        $total_220 = 0;
        $exportData = new class($result, $date, $total_110, $total_220) implements FromCollection, WithHeadings, WithColumnWidths {
            private $data;
            private $day;
            private $total_110;
            private $total_220;

            public function __construct($data, $date, $total_110, $total_220)
            {
                $this->data = $data;
                $this->date = $date;
                $this->total_110 = $total_110;
                $this->total_220 = $total_220;
            }

            public function collection()
            {
                $filteredData = $this->data->map(function ($item) {
                    $item->startdate = $this->date;
                    $item->enddate = $this->date;
                    $item->monthly_total = $item->monthly_amount + $item->monthly_amount_220;
                    $this->total_110 += $item->monthly_amount;
                    $this->total_220 += $item->monthly_amount_220;
                    return [
                        '房號' => $item->name,
                        '開始年月' => $item->startdate,
                        '結束年月' => $item->enddate,
                        '用電總計(110V)' => $item->monthly_amount,
                        '用電總計(220V)' => $item->monthly_amount_220,
                        '用電總計' => $item->monthly_total,
                        
                    ];
                });
                $filteredData->push([
                    '房號' => '總計',
                    '開始年月' => '',
                    '結束年月' => '',
                    '用電總計(110V)' => $this->total_110,
                    '用電總計(220V)' => $this->total_220,
                    '用電總計' => $this->total_110 + $this->total_220,
                ]);
                return $filteredData;
            }
            public function headings(): array
            {
                return [
                    '房號',
                    '開始年月',
                    '結束年月',
                    '用電總計(110V)',
                    '用電總計(220V)',
                    '用電總計',
                ];
            }
            public function columnWidths(): array
            {
                return [
                    'A' => 10, // 房號欄位寬度為 10
                    'B' => 15, // 開始年月欄位寬度為 20
                    'C' => 15, // 結束年月欄位寬度為 20
                    'D' => 15, // 用電總計(110V)欄位寬度為 15
                    'E' => 15, // 用電總計(220V)欄位寬度為 15
                    'F' => 10, // 用電總計欄位寬度為 10
                ];
            }
        };
        
        return Excel::download($exportData, 'power_consumption_d.xlsx');
    }
}
