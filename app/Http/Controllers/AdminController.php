<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\Loglist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;


class AdminController extends Controller
{
    public function login_in(Request $request) {   //帳號登入
        $username = $request->input('username');
        $password = $request->input('pwd');
        $user = Admin::login($username, $password);
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
                return redirect()->intended('/');
            }
        } else {
            // 登入失敗
            return back()->withErrors(['error' => '帳號或密碼不正確']);
        }
    }
    public function login_out() {   // 帳號登出
        Auth::logout();
        session()->forget(['admin_user']);
        return redirect()->intended('/');
    }

    public function register(Request $request) {   //帳號註冊
        $username = $request->input('username');
        $cname = $request->input('cname');
        $password = $request->input('password');
        $email = $request->input('email');
        $machine = $request->input('machine');
        $machine_mac = new Collection(); //  Laravel類別
        $error =[];

        foreach($machine as $row){
            $machine_check = Admin::machine_search($row); //序號搜尋
            if ($machine_check instanceof Collection) {
                foreach($machine_check as $subRow){
                    $machine_mac->push($subRow);
                }
            } else {
                if ($machine_check == 1) { 
                    $error[] ="序號不存在，請重新輸入序號！！";
                }elseif($machine_check == 2){ 
                    $error[] ="序號已被註冊！！";
                }
            }
        }
        if(empty($error)){
            $register_check = Admin::register_search($username);//註冊搜尋
            if ($register_check) {
                $error[] ="帳號已被註冊";
            } else {//成功直接登入
                $machine_insert = Admin::machine_insert($username, $cname, $password, $email, $machine_mac); 
                $user = Admin::login($username, $password);
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
                        return redirect()->intended('/manage');
                    }
                }
            }
        }
        return back()->withErrors($error);
    }

    public function edit(Request $request)//帳號修改
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
            $user = Admin::login($username, $old_pwd);
            if ($user) {
                Admin::updatePassword($username, $old_pwd, $new_pwd);
                $content = "帳號id:".session('admin_user.id')."；".session('admin_user.username')."密碼更新；舊密碼:".$old_pwd."；新密碼:".$new_pwd;
                Loglist::log($content, session('admin_user.identity'));
                return redirect()->back()->with('success', '密碼更新成功！');
            }else{
                $error[] = "舊密碼錯誤";
                return back()->withErrors($error);
            }
        }
    }

    public function forget(Request $request)//忘記密碼
    {
        
        $username = $request->input('user');    
        $mail = $request->input('mail'); 
        $error[] = "";
        $register_check = Admin::register_search($username);//帳號搜尋
        
        if ($register_check) {
            if($register_check->email == $mail){
                $randomString = Str::random(10, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
                $newPassword = Hash::make($randomString);
                Admin::where('username', $username)->update(['password' => $newPassword]);
                Mail::raw('新密碼為: ' . $randomString, function (Message $message) use ($mail) {
                    $message->to($mail)
                        ->subject('Your Password');
                });
                return redirect()->intended('/');
            }else{
                $error[] = "E-mail與註冊時填的不一致";
                return back()->withErrors($error);
            }

        } else{
            $error[] = "沒有此帳號!";
            return back()->withErrors($error);
        }
    }
}