<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use App\brand;
use App\Http\Requests;
use Illiminate\support\Facades\Redirect;
session_start();

class brandManage extends Controller
{
    public function authLogin(){
        $admin_id =  Session::get('admin_id');
        if($admin_id){
            return Redirect('dashboard');
        } else{
            return Redirect('admin-login')->send();
        }
    }
    public function brandList(){
        $this->authLogin();
        $brand_list = brand::orderBy('brand_id','desc')->get();
        $manage_brand = view('admin-pages.brand-list')->with('brand_list',$brand_list);
        return view('admin-layout')->with('admin-pages.brand-list',$manage_brand);
    }
    public function brandAdd(){
        $this->authLogin();
        return view("admin-pages.brand-add");
    }
    public function addBrandAction(Request $request){
        $this->authLogin();
        $data = array();
        $data['brand_name']= $request->brand_name;
        $data['status']= $request->status;

        $brand_id = DB::table('tbl_brand')->insertGetId($data);
        Session::put('message','Create new brand successful!');
        return Redirect('/brand-add');
    }
    public function brandEdit($brand_id){
        $this->authLogin();
        $brand_edit = brand::where('brand_id',$brand_id)->get();
        $manage_brand = view('admin-pages.brand-edit')->with('brand_edit', $brand_edit);
        return view('admin-layout')->with('admin-pages.brand-edit',$manage_brand);
    }
    public function brandUpdate(Request $request, $brand_id){
        $data = $request->all();
        $brand = brand::find($brand_id);
        $brand->brand_name = $data['brand_name'];
        $brand->save();

        Session::put('message','Update brand successful!');
        return redirect('brand-list');
    }
    public function displayBrand($brand_id){
        DB::table('tbl_brand')->where('brand_id', $brand_id)->update(['status'=>1]);
        Session::put('message','Display brand successful!');
        return redirect('brand-list');
    }
    public function hideBrand($brand_id){
        DB::table('tbl_brand')->where('brand_id', $brand_id)->update(['status'=>0]);
        Session::put('message','Hide brand successful!');
        return redirect('brand-list');
    }
    public function brandDel($brand_id){
        DB::table('tbl_brand')->where('brand_id',$brand_id)->delete();
        Session::put('message','Delete brand successful!');
        return redirect('brand-list');
    }

    // ===================================

    public function productByBrand(Request $request, $brand_id){
        $meta_desc = "PRO.N - Buy the hottest sneakers including Adidas Yeezy and Retro Jordans, Supreme streetwear, trading cards, collectibles, designer handbags and luxury ...";
        $meta_keywords = "sneaker, streetwear, watches, buy sneaker, buy watches";
        $meta_title = "PRO.N - Product";
        $url_canonical = $request->url();

        $category_item = DB::table('tbl_category')->where('status','1')->orderby('category_id','asc')->get();
        $brand_item = DB::table('tbl_brand')->where('status','1')->orderby('brand_id','desc')->get();
        $brand_name = DB::table('tbl_brand')->where('tbl_brand.brand_id',$brand_id)->limit(1)->get();

        $product_item = DB::table('tbl_product')
        ->join('tbl_category','tbl_category.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_product.brand_id',$brand_id)
        ->where('tbl_product.status','1')
        ->limit(6)->orderby('product_id','desc')->get();

        return view('pages.brand.show-product-by-brand')->with(compact('category_item','brand_item','brand_name','product_item','meta_desc','meta_keywords','meta_title','url_canonical'));
    }
}
