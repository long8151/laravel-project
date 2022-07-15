<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use App\adminLogin;
use App\Http\Requests;
use Illiminate\support\Facades\Redirect;
session_start();

class adminController extends Controller
{
    public function authLogin(){
        $admin_id =  Session::get('admin_id');
        if($admin_id){
            return Redirect('dashboard');
        } else{
            return Redirect('admin-login')->send();
        }
    }
    public function adminLogin(){
        return view('admin-login');
    }
    public function dashboard(){
        $this->authLogin();
        return view('admin-pages.dashboard');
    }
    public function loginAction(Request $request){
        $email = $request->email;
        $password = $request->password;

        $result = DB::table('tbl_admin')->where('email',$email)->where('password',$password)->first();
        if($result){
            Session::put('fullname',$result->fullname);
            Session::put('admin_id',$result->admin_id);
            return Redirect('/dashboard');
        } else{
            Session::put('message','Email or password is incorrect!');
            return Redirect('/admin-login');
        }
    }
    public function logoutAction(){
        Session::put('email',null);
        Session::put('admin_id',null);
        return view('admin-login');
    }

    // public function loginFacebook(){
    //     return Socialite::driver('facebook')->redirect();
    // }
    // public function callbackFacebook(){
    //     $provider = Socialite::driver('facebook')->user();
    //     $account = Social::where('provider', 'facebook')->where('provider_user_id', $provider->getId())->first();
    //     if($account){
    //         $account_name = Login::where('admin_id', $account->user)->first();
    //         Session::put('fullname', $account_name->fullname);
    //         Session::put('admin_id', $account_name->admin_id);
    //         Session::put('login_normal',true);
    //         return redirect('/dashboard')->with('message', 'Login admin successful!');
    //     } else{
    //         $admin_login = new Social([
    //             'provider_user_id' => $provider->getId(),
    //             'provider' => 'facebook'
    //         ]);

    //         $pron = Login::where('email', $provider->getEmail())->first();

    //         if(!$pron){
    //             $pron = Login::create([
    //                 'email' => $provider->getEmail(),
    //                 'fullname' => $provider->getName(),
    //                 'password' => '',
    //                 'phonenumber' => ''
    //             ]);
    //         }
    //         $admin_login->Login()->associate($pron);
    //         $admin_login->save();

    //         $account_name = Login::where('admin_id', $admin_login->user)->first();
    //         Session::put('fullname', $account_name->fullname);
    //         Session::put('admin_id', $account_name->admin_id);

    //         return redirect('/dashboard')->with('message','Login admin successful!');
    //     }
    // }
}
