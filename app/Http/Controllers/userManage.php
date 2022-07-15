<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use App\Http\Requests;
use Illiminate\support\Facades\Redirect;
session_start();

class userManage extends Controller
{
    public function authLogin(){
        $admin_id =  Session::get('admin_id');
        if($admin_id){
            return Redirect('dashboard');
        } else{
            return Redirect('admin-login')->send();
        }
    }
    public function adminUserManage($admin_id){
        $this->authLogin();
        $admin_user = DB::table('tbl_admin')->where('admin_id', $admin_id)->get();
        $manage_admin = view('admin-pages.admin-user-manage')->with('admin_user', $admin_user);

        return view('admin-layout')->with('admin-pages.admin-user-manage',$manage_admin);
    }
    public function adminUserUpdate(Request $request, $admin_id){
        $data = array();
        $data['email'] = $request->email;
        $data['password'] = $request->password;
        $data['fullname'] = $request->fullname;
        $data['phonenumber'] = $request->phonenumber;
        $data['status'] = $request->status;

        DB::table('tbl_admin')->where('admin_id',$admin_id)->update($data);
        Session::put('message','Update admin user successful!');
        return redirect('admin-user-manage/{admin_id}');
    }
    public function clientUserManage(){
        $this->authLogin();
        $client_user_manage = DB::table('tbl_client')->orderby('tbl_client.client_id','desc')->get();
        $manage_client = view('admin-pages.client-user-manage')->with('client_user_manage',$client_user_manage);
        return view('admin-layout')->with('admin-pages.client-user-manage',$manage_client);
    }
    public function activeClient($client_id){
        DB::table('tbl_client')->where('client_id', $client_id)->update(['status'=>1]);
        Session::put('message','Active client successful!');
        return redirect('client-user-manage');
    }
    public function unactiveClient($client_id){
        DB::table('tbl_client')->where('client_id', $client_id)->update(['status'=>0]);
        Session::put('message','Unactive client successful!');
        return redirect('client-user-manage');
    }
    public function clientDel($client_id){
        DB::table('tbl_client')->where('client_id',$client_id)->delete();
        Session::put('message','Delete client successful!');
        return redirect('client-user-manage');
    }
}
