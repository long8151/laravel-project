<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use App\category;
use App\Http\Requests;
use Illiminate\support\Facades\Redirect;
session_start();

class categoryManage extends Controller
{
    public function authLogin(){
        $admin_id =  Session::get('admin_id');
        if($admin_id){
            return Redirect('dashboard');
        } else{
            return Redirect('admin-login')->send();
        }
    }
    public function categoryList(){
        $this->authLogin();
        $category_list = DB::table('tbl_category')->orderby('category_id','desc')->get();
        $manage_category = view('admin-pages.category-list')->with('category_list',$category_list);
        return view('admin-layout')->with('admin-pages.category-list',$manage_category);
    }
    public function categoryAdd(){
        $this->authLogin();
        return view("admin-pages.category-add");
    }
    public function addCategoryAction(Request $request){
        $this->authLogin();
        $data = array();
        $data['category_name']= $request->category_name;
        $data['status']= $request->status;

        $brand_id = DB::table('tbl_category')->insertGetId($data);
        Session::put('message','Create new category successful!');
        return Redirect('/category-add');
    }
    public function categoryEdit($category_id){
        $this->authLogin();
        $category_edit = category::where('category_id',$category_id)->get();
        $manage_category = view('admin-pages.category-edit')->with('category_edit', $category_edit);
        return view('admin-layout')->with('admin-pages.category-edit',$manage_category);
    }
    public function categoryUpdate(Request $request, $category_id){
        $data = array();
        $data['category_name'] = $request->category_name;

        DB::table('tbl_category')->where('category_id',$category_id)->update($data);
        Session::put('message','Update category successful!');
        return redirect('category-list');
    }
    public function displayCategory($category_id){
        DB::table('tbl_category')->where('category_id', $category_id)->update(['status'=>1]);
        Session::put('message','Display category successful!');
        return redirect('category-list');
    }
    public function hideCategory($category_id){
        DB::table('tbl_category')->where('category_id', $category_id)->update(['status'=>0]);
        Session::put('message','Hide category successful!');
        return redirect('category-list');
    }
    public function categoryDel($category_id){
        DB::table('tbl_category')->where('category_id',$category_id)->delete();
        Session::put('message','Delete category successful!');
        return redirect('category-list');
    }

    // ==========================================

    public function productByCategory(Request $request, $category_id){
        $meta_desc = "PRO.N - Buy the hottest sneakers including Adidas Yeezy and Retro Jordans, Supreme streetwear, trading cards, collectibles, designer handbags and luxury ...";
        $meta_keywords = "sneaker, streetwear, watches, buy sneaker, buy watches";
        $meta_title = "PRO.N - Product";
        $url_canonical = $request->url();

        $category_item = DB::table('tbl_category')->where('status','1')->orderby('category_id','asc')->get();
        $brand_item = DB::table('tbl_brand')->where('status','1')->orderby('brand_id','desc')->get();
        $category_name = DB::table('tbl_category')->where('tbl_category.category_id',$category_id)->limit(1)->get();

        $product_item = DB::table('tbl_product')
        ->join('tbl_category','tbl_category.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_product.category_id',$category_id)
        ->where('tbl_product.status','1')
        ->limit(6)->orderby('product_id','desc')->get();

        return view('pages.category.show-product-by-category')->with(compact('category_item','brand_item','category_name','product_item','meta_desc','meta_keywords','meta_title','url_canonical'));
    }
}
