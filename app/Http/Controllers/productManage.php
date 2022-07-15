<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use App\Http\Requests;
use Illiminate\support\Facades\Redirect;
session_start();

class productManage extends Controller
{
    public function authLogin(){
        $admin_id =  Session::get('admin_id');
        if($admin_id){
            return Redirect('dashboard');
        } else{
            return Redirect('admin-login')->send();
        }
    }
    public function productList(){
        $this->authLogin();
        $product_list = DB::table('tbl_product')
        // ->join('tbl_category','tbl_category.category_id','=','tbl_product.category_id')
        // ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->orderby('tbl_product.product_id','desc')->get();

        $manage_product = view('admin-pages.product-list')->with('product_list',$product_list);
        return view('admin-layout')->with('admin-pages.product-list',$manage_product);
    }
    public function productAdd(){
        $this->authLogin();
        $cat_id = DB::table('tbl_category')->orderby('category_id','desc')->get();
        $brand_id = DB::table('tbl_brand')->orderby('brand_id','desc')->get();

        return view('admin-pages.product-add')->with('cat_id', $cat_id)->with('brand_id', $brand_id);
    }
    public function addProductAction(Request $request){
        $data = array();
        $data['category_id'] = $request->category_id;
        $data['brand_id'] = $request->brand_id;
        $data['product_name'] = $request->product_name;
        $data['price'] = $request->price;
        $data['quantity'] = $request->quantity;
        $data['description'] = $request->description;
        $data['status'] = $request->status;
        $get_img = $request->file('image');

        if($get_img){
            $get_url_img = $get_img->getClientOriginalName();
            $name_img = current(explode('.',$get_url_img));
            $new_img = $name_img.rand(0,99).'.'.$get_img->getClientOriginalExtension();
            $get_img -> move('public/uploads/product', $new_img);
            $data['image'] = $new_img;

            DB::table('tbl_product')->insert($data);
            Session::put('message','Create new product successful!');
            return redirect('product-add');
        }
        $data['image'] = '';
        DB::table('tbl_product')->insert($data);
        Session::put('message','Create new product successful!');
        return redirect('product-add');
    }
    public function productEdit($product_id){
        $this->authLogin();
        $cat_id = DB::table('tbl_category')->orderby('category_id','desc')->get();
        $bra_id = DB::table('tbl_brand')->orderby('brand_id','desc')->get();

        $product_edit = DB::table('tbl_product')->where('product_id', $product_id)->get();
        $manage_product = view('admin-pages.product-edit')->with('product_edit', $product_edit)->with('cat_id',$cat_id)->with('bra_id',$bra_id);

        return view('admin-layout')->with('admin-pages.product-edit',$manage_product);
    }
    public function productUpdate(Request $request, $product_id){
        $data = array();
        $data['category_id'] = $request->category_id;
        $data['brand_id'] = $request->brand_id;
        $data['product_name'] = $request->product_name;
        $data['price'] = $request->price;
        $data['quantity'] = $request->quantity;
        $data['description'] = $request->description;
        $get_img = $request->file('image');

        if($get_img){
            $get_url_img = $get_img->getClientOriginalName();
            $name_img = current(explode('.',$get_url_img));
            $new_img = $name_img.rand(0,99).'.'.$get_img->getClientOriginalExtension();
            $get_img -> move('public/uploads/product', $new_img);
            $data['image'] = $new_img;

            DB::table('tbl_product')->update($data);
            Session::put('message','Update product successful!');
            return redirect('product-list');
        }

        DB::table('tbl_product')->where('product_id',$product_id)->update($data);
        Session::put('message','Update product successful!');
        return redirect('product-list');
    }
    public function displayProduct($product_id){
        DB::table('tbl_product')->where('product_id', $product_id)->update(['status'=>1]);
        Session::put('message','Display product successful!');
        return redirect('product-list');
    }
    public function hideProduct($product_id){
        DB::table('tbl_product')->where('product_id', $product_id)->update(['status'=>0]);
        Session::put('message','Hide product successful!');
        return redirect('product-list');
    }
    public function productDel($product_id){
        DB::table('tbl_product')->where('product_id',$product_id)->delete();
        Session::put('message','Delete product successful!');
        return redirect('product-list');
    }
}
