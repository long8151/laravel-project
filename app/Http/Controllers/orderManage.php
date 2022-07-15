<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use App\Http\Requests;
use Illiminate\support\Facades\Redirect;
session_start();

class orderManage extends Controller
{
    public function authLogin(){
        $admin_id =  Session::get('admin_id');
        if($admin_id){
            return Redirect('dashboard');
        } else{
            return Redirect('admin-login')->send();
        }
    }
    public function orderList(){
        $this->authLogin();
        $order_list = DB::table('tbl_order')
        ->join('tbl_client','tbl_order.client_id','=','tbl_client.client_id')
        ->select('tbl_order.*','tbl_client.client_fullname')
        ->orderby('tbl_order.order_id','desc')->get();

        $manage_order = view('admin-pages.order-list')->with('order_list',$order_list);
        return view('admin-layout')->with('admin-pages.order-list',$manage_order);
    }
    public function orderWaiting(){
        $this->authLogin();
        $order_waiting_list =
        DB::table('tbl_order')->where('status', 0)->orderby('tbl_order.order_id','desc')->get();

        $manage_order = view('admin-pages.order-waiting')->with('order_waiting_list',$order_waiting_list);
        return view('admin-layout')->with('admin-pages.order-waiting',$manage_order);
    }
    public function orderApproveAction(Request $request, $order_id){
        DB::table('tbl_order')->where('order_id', $order_id)->update(['status'=>1]);
        Session::put('message','Approve order successful!');
        return redirect('order-list');
    }
    public function orderDetail($order_id){
        $this->authLogin();
        $order_item = DB::table('tbl_order')
        ->join('tbl_client','tbl_order.client_id','=','tbl_client.client_id')
        ->join('tbl_checkout_client_info','tbl_order.checkClientInfo_id','=','tbl_checkout_client_info.checkClientInfo_id')
        ->join('tbl_order_detail','tbl_order.order_id','=','tbl_order_detail.order_id')
        ->select('tbl_checkout_client_info.*','tbl_client.*','tbl_order_detail.*')->first();

        $manage_order_detail = view('admin-pages.order-detail')->with('order_item', $order_item);

        return view('admin-layout')->with('admin-pages.order-detail',$manage_order_detail);
    }
    public function orderCancel($order_id){
        DB::table('tbl_order')->where('order_id',$order_id)->delete();
        Session::put('message','Cancel order successful!');
        return redirect('product-list');
    }
}
