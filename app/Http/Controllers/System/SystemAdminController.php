<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\System\SystemAdmin;
use App\Models\Loglist;
use Illuminate\Support\Facades\Auth;

class SystemAdminController extends Controller
{
    public function login_in(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('pwd');
        $user = SystemAdmin::login($username, $password);
        if ($user) {
            if (Auth::attempt(['username' => $username, 'password' => $password])) {
                // 登入成功
                session([
                        'admin_user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'cname' => $user->cname,
                        'pwd' => $user->password,
                        'identity' => $user->identity,
                    ]
                ]);
                $content = "帳號id:".session('admin_user.id')."；".session('admin_user.username')."登入";
                Loglist::log($content, $user->identity);
                return redirect()->intended('/system/manage');
            }
        } else {
            // 登入失敗
            return back()->withErrors(['error' => '帳號或密碼不正確']);
        }
    }
    public function login_out()
    {
        Auth::logout();
        session()->forget(['admin_user']);
        return redirect()->intended('/system');

    }
    public function edit(Request $request)
    {
        $error =[];
        $username = $request->input('account');    
        $old_pwd = $request->input('old_pwd'); 
        $new_pwd = $request->input('new_pwd'); 
        $new_pwd_check = $request->input('new_pwd_check'); 
        if(empty($old_pwd)){
            $error[] ="您的舊密碼沒填或輸入錯誤!!";
        }
        if(empty($new_pwd)){
            $error[] ="您的新密碼沒填或輸入錯誤!!";
        }
        if(empty($new_pwd_check)){
            $error[] ="您的確認密碼沒填或輸入錯誤!!";
        }
        if ($new_pwd != $new_pwd_check) {
            // 新密碼與確認密碼不相同
            $error[] = "新密碼與確認密碼不相同！";
        }
        if ($old_pwd == $new_pwd) {
            // 舊密碼和新密碼相同
            $error[] = "新密碼不能與舊密碼相同！";
        }
        if (count($error) > 0) {
            // 如果有錯誤，返回並顯示錯誤訊息
            return back()->withErrors($error);
        } else {
            // 如果沒有錯誤，進行密碼驗證
            $user = SystemAdmin::login($username, $old_pwd);
            if ($user) {
                SystemAdmin::updatePassword($username, $old_pwd, $new_pwd);
                $content = "帳號id:".session('admin_user.id')."；".session('admin_user.username')."密碼更新；舊密碼:".$old_pwd."；新密碼:".$new_pwd;
                Loglist::log($content, session('admin_user.identity'));
                return redirect()->back()->with('success', '密碼更新成功！');
            }else{
                $error[] = "舊密碼錯誤";
                return back()->withErrors($error);
            }
        }
    }
}