<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PowerController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\DeviceController;

use App\Http\Controllers\System\SystemAdminController;
use App\Http\Controllers\System\SystemManageController;
use App\Http\Controllers\System\SystemLogController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//前台
Route::get('/', function () {
    return view('index');
})->name('home');

Route::post('admin_login', [AdminController::class,'login_in']);
Route::get('admin_logout', [AdminController::class,'login_out']);

Route::get('/admin_edit', function () {
    return view('admin_edit');
});
Route::post('admin_edit', [AdminController::class,'edit']);

Route::get('/admin_register', function () {
    return view('admin_register');
});
Route::post('admin_register', [AdminController::class,'register']);

Route::get('/admin_forget', function () {
    return view('admin_forget');
});
Route::post('admin_forget', [AdminController::class,'forget']);

Route::get('/manage', [ManageController::class, 'index'])->name('manage');

Route::get('/power-record', function () {
    return view('power-record');
});

Route::match(['get', 'post'], 'power-record', [PowerController::class, 'power_record']);
Route::match(['get', 'post'], 'power_record_excel', [ExcelController::class, 'power_record_excel']);
Route::match(['get', 'post'], 'power-nowmeter', [PowerController::class,'power_nowmeter']);
Route::match(['get', 'post'], 'power_nowmeter_excel', [ExcelController::class, 'power_nowmeter_excel']);

Route::get('/power-consumption-d', function () {
    return view('power-consumption-d');
});
Route::match(['get', 'post'], 'power-consumption-d', [PowerController::class,'power_consumption_d']);
Route::match(['get', 'post'], 'power_consumption_d_excel', [ExcelController::class, 'power_consumption_d_excel']);

Route::get('/power-consumption-m', function () {
    return view('power-consumption-m');
});
Route::match(['get', 'post'], 'power-consumption-m', [PowerController::class,'power_consumption_m']);
Route::match(['get', 'post'], 'power_consumption_m_excel', [ExcelController::class, 'power_consumption_m_excel']);

Route::get('/rate', function () {
    return view('rate');
});
Route::match(['get', 'post'], 'rate', [PowerController::class,'rate_search']);
Route::match(['get', 'post'], 'rate_update', [PowerController::class,'rate_update']);

Route::get('/power-switch', function () {
    return view('power-switch');
});
Route::match(['get', 'post'], 'power-switch', [PowerController::class,'power_switch_search']);
Route::post('power-switch-update', [PowerController::class, 'power_switch_update']);

Route::match(['get', 'post'], 'device', [DeviceController::class, 'index']);
Route::post('revise_device', [DeviceController::class, 'revise_device']);
Route::match(['get', 'post'], 'add_device', [DeviceController::class, 'add_device']);

//後台
// Route::get('/system/', function () {
//     return view('system.index');
// });

// Route::prefix('/system')->group(function () {
//     Route::post('/admin_login', [SystemAdminController::class,'login_in']);
//     Route::get('/admin_logout', [SystemAdminController::class,'login_out']);
//     Route::get('/manage', [SystemManageController::class, 'index'])->name('manage');
//     Route::get('/log-frontdesk', [SystemLogController::class, 'index'])->name('log_frontdesk');
    
// });