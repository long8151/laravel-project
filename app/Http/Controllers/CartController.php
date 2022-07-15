<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Cart;
use Session;
use App\Http\Requests;
use Illiminate\support\Facades\Redirect;
session_start();

class cartController extends Controller
{
    //
    public function addCart(Request $request){
        $product_id = $request->productId_hidden;
        $quantity = $request->qty;
        $product_info = DB::table('tbl_product')->where('product_id',$product_id)->first();

        $data['id'] = $product_info->product_id;
        $data['qty'] = $quantity;
        $data['name'] = $product_info->product_name;
        $data['price'] = $product_info->price;
        $data['weight'] = '29';
        $data['options']['image'] = $product_info->image;
        Cart::add($data);
        Cart::setGlobalTax(10);
        // Cart::setGlobalDiscount(20);
        return Redirect('/show-cart');
    }

    public function showCart(Request $request){
        $meta_desc = "PRO.N - Buy the hottest sneakers including Adidas Yeezy and Retro Jordans, Supreme streetwear, trading cards, collectibles, designer handbags and luxury ...";
        $meta_keywords = "sneaker, streetwear, watches, buy sneaker, buy watches";
        $meta_title = "PRO.N - Shopping Cart";
        $url_canonical = $request->url();

        $category_item = DB::table('tbl_category')->where('status','1')->orderby('category_id','asc')->get();
        $brand_item = DB::table('tbl_brand')->where('status','1')->orderby('brand_id','desc')->get();

        return view('pages.cart.show-cart')->with(compact('category_item','brand_item','meta_desc','meta_keywords','meta_title','url_canonical'));
    }

    public function delCart($rowId){
        Cart::update($rowId,0);
        return redirect('/show-cart');
    }

    public function updateCartQty(Request $request){
        $rowId = $request->rowId_cart;
        $qty = $request->cart_quantity;
        Cart::update($rowId,$qty);
        return redirect('/show-cart');
    }
}
