<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Cart;
use Session;
use App\Http\Requests;
use Illiminate\support\Facades\Redirect;
session_start();

class checkoutController extends Controller
{
    //
    public function checkout(Request $request){
        $meta_desc = "PRO.N - Buy the hottest sneakers including Adidas Yeezy and Retro Jordans, Supreme streetwear, trading cards, collectibles, designer handbags and luxury ...";
        $meta_keywords = "sneaker, streetwear, watches, buy sneaker, buy watches";
        $meta_desc = "PRO.N - Checkout product";
        $meta_title = "PRO.N - Checkout";
        $url_canonical = $request->url();
        $client_id = Session::get('client_id');

        $client_user = DB::table('tbl_client')
        ->join('tbl_checkout_client_info','tbl_checkout_client_info.client_id','=','tbl_client.client_id')
        ->where('tbl_client.client_id',$client_id)->get();

        return view('pages.checkout.checkout')->with(compact('meta_desc','meta_keywords','meta_title','url_canonical','client_user'));
    }
    public function checkoutAction(Request $request){
        $data = array();
        $data['email']= $request->email;
        $data['client_id']= Session::get('client_id');
        $data['client_name']= $request->client_name;
        $data['phonenumber']= $request->phonenumber;
        $data['address']= $request->address;
        $data['note']= $request->note;
        $checkClientInfo_id = DB::table('tbl_checkout_client_info')->insertGetId($data);

        Session::put('checkClientInfo_id',$checkClientInfo_id);
        Session::put('client_name',$request->client_name);

        return Redirect('/payment');
    }
    public function payment(Request $request){
        $meta_desc = "PRO.N - Buy the hottest sneakers including Adidas Yeezy and Retro Jordans, Supreme streetwear, trading cards, collectibles, designer handbags and luxury ...";
        $meta_keywords = "sneaker, streetwear, watches, buy sneaker, buy watches";
        $meta_desc = "PRO.N - Payment method";
        $meta_title = "PRO.N - Payment";
        $url_canonical = $request->url();

        $category_item = DB::table('tbl_category')->where('status','1')->orderby('category_id','desc')->get();
        $brand_item = DB::table('tbl_brand')->where('status','1')->orderby('brand_id','desc')->get();

        return view('pages.checkout.payment')->with(compact('category_item','brand_item','meta_desc','meta_keywords','meta_title','url_canonical'));
    }
    public function order(Request $request){
        $data = array();
        $data['payment_method'] = $request->payment_option;
        $payment_id = DB::table('tbl_payment')->insertGetId($data);

        $order_data = array();
        $order_data['client_id'] = Session::get('client_id');
        $order_data['checkClientInfo_id'] = Session::get('checkClientInfo_id');
        $order_data['payment_id'] = $payment_id;
        $order_data['total'] = Cart::total();
        $order_data['status'] = 0;
        $order_id = DB::table('tbl_order')->insertGetId($order_data);

        $content = Cart::content();
        foreach($content as $v_content){
            $order_detail_data['order_id'] = $order_id;
            $order_detail_data['product_id'] = $v_content->id;
            $order_detail_data['product_name'] = $v_content->name;
            $order_detail_data['total'] = $v_content->price;
            $order_detail_data['quantity'] = $v_content->qty;
            DB::table('tbl_order_detail')->insert($order_detail_data);
        }
        if($data['payment_method']==1){
            echo 'Direct bank transfer';
        } elseif($data['payment_method']==2){
            $meta_desc = "PRO.N - Buy the hottest sneakers including Adidas Yeezy and Retro Jordans, Supreme streetwear, trading cards, collectibles, designer handbags and luxury ...";
            $meta_keywords = "sneaker, streetwear, watches, buy sneaker, buy watches";
            $meta_title = "PRO.N - Payment";
            $url_canonical = $request->url();
            Cart::destroy();

            $category_item = DB::table('tbl_category')->where('status','1')->orderby('category_id','desc')->get();
            $brand_item = DB::table('tbl_brand')->where('status','1')->orderby('brand_id','desc')->get();

            return view('pages.checkout.cash-payment')->with(compact('category_item','brand_item','meta_desc','meta_keywords','meta_title','url_canonical'));
        } else{
            echo 'Paypal';
        }
    }
}
