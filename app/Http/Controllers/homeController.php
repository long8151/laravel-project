<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use Socialite;
use App\Social;
use App\Login;
use App\product;
use App\Http\Requests;
use App\Rules\Captcha;
use Validators;
use Illiminate\support\Facades\Redirect;
session_start();

class homeController extends Controller
{
    public function test(){
        return view('test');
    }
    public function index(Request $request){
        $meta_desc = "PRO.N - Buy the hottest sneakers including Adidas Yeezy and Retro Jordans, Supreme streetwear, trading cards, collectibles, designer handbags and luxury ...";
        $meta_keywords = "sneaker, streetwear, watches, buy sneaker, buy watches";
        $meta_title = "PRO.N - Sneakers, Streetwear, Warches";
        $url_canonical = $request->url();

        $category_item = DB::table('tbl_category')->where('status','1')->orderby('category_id','asc')->get();
        $brand_item = DB::table('tbl_brand')->where('status','1')->orderby('brand_id','desc')->get();

        $show_by_sneaker = DB::table('tbl_category')
        ->join('tbl_product','tbl_product.category_id','=','tbl_category.category_id')
        ->where('tbl_category.category_id','1')->limit(1)->get();

        $show_by_streetwear = DB::table('tbl_category')
        ->join('tbl_product','tbl_product.category_id','=','tbl_category.category_id')
        ->where('tbl_category.category_id','2')->limit(1)->get();

        $show_by_watches = DB::table('tbl_category')
        ->join('tbl_product','tbl_product.category_id','=','tbl_category.category_id')
        ->where('tbl_category.category_id','3')->limit(1)->get();

        $sneaker_item = DB::table('tbl_product')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_product.category_id','1')
        ->where('tbl_product.status','1')
        ->limit(6)->orderby('product_id','desc')->get();

        $streetwear_item = DB::table('tbl_product')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_product.category_id','2')
        ->where('tbl_product.status','1')
        ->limit(6)->orderby('product_id','desc')->get();

        $watches_item = DB::table('tbl_product')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_product.category_id','3')
        ->where('tbl_product.status','1')
        ->limit(6)->orderby('product_id','desc')->get();

        return view('pages.home')->with(compact('category_item','brand_item','sneaker_item','streetwear_item','watches_item','show_by_sneaker','show_by_streetwear','show_by_watches','meta_desc','meta_keywords','meta_title','url_canonical'));
    }
    public function searchProduct(Request $request){
        $meta_desc = "PRO.N - Buy the hottest sneakers including Adidas Yeezy and Retro Jordans, Supreme streetwear, trading cards, collectibles, designer handbags and luxury ...";
        $meta_keywords = "sneaker, streetwear, watches, buy sneaker, buy watches";
        $meta_title = "PRO.N - Sneakers, Streetwear, Warches";
        $url_canonical = $request->url();

        $keyword = $request->keyword_submit;

        $category_item = DB::table('tbl_category')->where('status','1')->orderby('category_id','desc')->get();
        $brand_item = DB::table('tbl_brand')->where('status','1')->orderby('brand_id','desc')->get();

        $search_item = DB::table('tbl_product')
        ->join('tbl_category','tbl_category.category_id','=','tbl_product.category_id')
        ->where('product_name','like','%'.$keyword.'%')->get();

        return view('pages.product.search-product')->with(compact('category_item','brand_item','search_item','meta_desc','meta_keywords','meta_title','url_canonical'));
    }

    // ============================PRODUCT==============================

    public function productAll(Request $request){
        $meta_desc = "PRO.N - Buy the hottest sneakers including Adidas Yeezy and Retro Jordans, Supreme streetwear, trading cards, collectibles, designer handbags and luxury ...";
        $meta_keywords = "sneaker, streetwear, watches, buy sneaker, buy watches";
        $meta_title = "PRO.N - Product";
        $url_canonical = $request->url();

        $category_item = DB::table('tbl_category')->where('status','1')->orderby('category_id','asc')->get();
        $brand_item = DB::table('tbl_brand')->where('status','1')->orderby('brand_id','desc')->get();

        $product_item = DB::table('tbl_product')
        ->join('tbl_category','tbl_category.category_id','=','tbl_product.category_id')
        ->where('tbl_product.status','1')
        ->limit(20)->orderby('product_id','desc')->get();

        return view('pages.product')->with(compact('category_item','brand_item','product_item','meta_desc','meta_keywords','meta_title','url_canonical'));
    }
    public function productDetail(Request $request, $product_id){
        $meta_desc = "PRO.N - Product detail";
        $meta_keywords = "sneaker, streetwear, watches, buy sneaker, buy watches";
        $meta_title = "PRO.N - Product Detail";
        $url_canonical = $request->url();

        $category_item = DB::table('tbl_category')->where('status','1')->get();
        $brand_item = DB::table('tbl_brand')->where('status','1')->get();

        $product_detail = DB::table('tbl_product')
        ->join('tbl_category','tbl_category.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_product.product_id',$product_id)->get();

        foreach($product_detail as $key => $value){
            $category_id = $value->category_id;
        }

        $related_product = DB::table('tbl_product')
        ->join('tbl_category','tbl_category.category_id','=','tbl_product.category_id')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_category.category_id',$category_id)
        ->whereNotIn('tbl_product.product_id',[$product_id])->limit(4)->get();

        return view('pages.product-detail')->with(compact('category_item','brand_item','product_detail','related_product','meta_desc','meta_keywords','meta_title','url_canonical'));
    }

    // ============================LOGIN-FORM==============================

    public function loginHome(){
        return view('login');
    }
    public function createClient(Request $request){
        $data = $request->validate([
            'client_email' => 'required',
            'password' => 'required',
            'client_fullname' => 'required',
            'client_phonenumber' => 'required',
            'client_address' => 'required',
            'g-recaptcha-response' => new Captcha()
        ]);
        $data = array();
        $data['client_email']= $request->client_email;
        $data['password']= $request->password;
        $data['client_fullname']= $request->client_fullname;
        $data['client_phonenumber']= $request->client_phonenumber;
        $data['client_address']= $request->client_address;

        $client_id = DB::table('tbl_client')->insertGetId($data);

        Session::put('client_id',$client_id);
        Session::put('client_fullname',$request->client_fullname);
        Session::put('message','Create client user successful!');

        return Redirect('/home');
    }
    public function loginClient(Request $request){
        $data = $request->validate([
            'client_email' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => new Captcha()
        ]);
        $client_email = $request->client_email;
        $password = $request->password;

        $result = DB::table('tbl_client')->where('client_email',$client_email)->where('password',$password)->first();
        if($result){
            Session::put('client_fullname',$result->client_fullname);
            Session::put('client_id',$result->client_id);
            return Redirect('/home');
        } else{
            Session::put('message','Email or password is incorrect!');
            return Redirect('/login');
        }
    }
    public function logoutClient(){
        Session::flush();
        return redirect('/home');

    }

    public function loginFacebook(){
        return Socialite::driver('facebook')->redirect();
    }
    public function callbackFacebook(){
        $provider = Socialite::driver('facebook')->user();
        $account = Social::where('provider', 'facebook')->where('provider_user_id', $provider->getId())->first();
        if($account){
            $account_name = Login::where('client_id', $account->user)->first();
            Session::put('client_fullname', $account_name->client_fullname);
            Session::put('client_id', $account_name->client_id);
            Session::put('login_normal',true);
            return redirect('/home')->with('message', 'Login successful!');
        } else{
            $fb_login = new Social([
                'provider_user_id' => $provider->getId(),
                'provider' => 'facebook'
            ]);

            $pron = Login::where('client_email', $provider->getEmail())->first();

            if(!$pron){
                $pron = Login::create([
                    'client_email' => $provider->getEmail(),
                    'client_fullname' => $provider->getName(),
                    'password' => '',
                    'client_phonenumber' => ''
                ]);
            }
            $fb_login->Login()->associate($pron);
            $fb_login->save();

            $account_name = Login::where('client_id', $fb_login->user)->first();
            Session::put('client_fullname', $account_name->client_fullname);
            Session::put('client_id', $account_name->client_id);

            return redirect('/home')->with('message','Login successful!');
        }
    }
    public function loginGoogle(){
        config(['services.google.redirect' => env('GOOGLE_URL')]);
        return Socialite::driver('google')->redirect();
   }
    public function callbackGoogle(){
        config(['services.google.redirect' => env('GOOGLE_URL')]);
        $users = Socialite::driver('google')->stateless()->user();

        $authUser = $this->findOrCreateUser($users,'google');
        if($authUser){
            $account_name = Login::where('client_id',$authUser->user)->first();
            Session::put('client_fullname',$account_name->client_fullname);
            Session::put('client_id',$account_name->client_id);
        } elseif($create_client){
            $account_name = Login::where('client_id',$authUser->user)->first();
            Session::put('client_fullname',$account_name->client_fullname);
            Session::put('client_id',$account_name->client_id);
        }
        return redirect('/home')->with('message', 'Login successful!');
    }
    public function findOrCreateUser($users, $provider){
        $authUser = Social::where('provider_user_id', $users->id)->first();
        if($authUser){
            return $authUser;
        }
        $gg_login = new Social([
            'provider_user_id' => $users->id,
            'provider' => strtoupper($provider)
        ]);

        $pron = Login::where('client_email',$users->email)->first();

            if(!$pron){
                $pron = Login::create([
                    'client_fullname' => $users->name,
                    'client_email' => $users->email,
                    'password' => '',

                    'client_phonenumber' => '',
                    'status' => 1
                ]);
            }
        $gg_login->login()->associate($pron);
        $gg_login->save();

        $account_name = Login::where('client_id',$authUser->user)->first();
        Session::put('client_fullname',$account_name->client_fullname);
        Session::put('client_id',$account_name->client_id);
        return redirect('/home')->with('message', 'Login successful!');
    }
}
