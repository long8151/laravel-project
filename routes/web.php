<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// =================================home=================================
Route::get('/', 'homeController@index');

Route::get('/home', 'homeController@index');

Route::post('/search-product', 'homeController@searchProduct');

// =================================product=================================
Route::get('/product', 'homeController@productAll');

Route::get('/show-product-by-category/{category_id}', 'categoryManage@productByCategory');

Route::get('/show-product-by-brand/{brand_id}', 'brandManage@productByBrand');

Route::get('/product-detail/{product_id}', 'homeController@productDetail');

// =================================login-home=================================
Route::get('/login', 'homeController@loginHome');

Route::post('/create-client', 'homeController@createClient');

Route::post('/login-client', 'homeController@loginClient');

Route::get('/logout-client', 'homeController@logoutClient');

// =================================checkout=================================
Route::get('/checkout', 'checkoutController@checkout');

Route::post('/checkout-action', 'checkoutController@checkoutAction');

Route::get('/payment', 'checkoutController@payment');

Route::post('/order', 'checkoutController@order');

// =================================cart=================================
Route::post('/add-cart', 'CartController@addCart');

Route::get('/show-cart', 'CartController@showCart');

Route::post('/update-cart-qty', 'CartController@updateCartQty');

Route::get('/del-cart/{rowId}', 'CartController@delCart');

// =================================admin=================================
Route::get('/dashboard', 'adminController@dashboard');

Route::get('/admin-login', 'adminController@adminLogin');

Route::post('/login-action', 'adminController@loginAction');

Route::get('/logout-action', 'adminController@logoutAction');

// =================================category-manage=================================
Route::get('/category-list', 'categoryManage@categoryList');

Route::get('/category-add', 'categoryManage@categoryAdd');

Route::post('/add-category-action', 'categoryManage@addCategoryAction');

Route::get('/display-category/{category_id}', 'categoryManage@displayCategory');

Route::get('/hide-category/{category_id}', 'categoryManage@hideCategory');

Route::get('/category-edit/{category_id}', 'categoryManage@categoryEdit');

Route::post('/category-update/{category_id}', 'categoryManage@categoryUpdate');

Route::get('/category-delete/{category_id}', 'categoryManage@categoryDel');

// =================================brand-manage=================================
Route::get('/brand-list', 'brandManage@brandList');

Route::get('/brand-add', 'brandManage@brandAdd');

Route::post('/add-brand-action', 'brandManage@addBrandAction');

Route::get('/brand-edit/{category_id}', 'brandManage@brandEdit');

Route::post('/brand-update/{category_id}', 'brandManage@brandUpdate');

Route::get('/display-brand/{category_id}', 'brandManage@displayBrand');

Route::get('/hide-brand/{category_id}', 'brandManage@hideBrand');

Route::get('/brand-delete/{category_id}', 'brandManage@brandDel');

// =================================product-manage=================================
Route::get('/product-list', 'productManage@productList');

Route::get('/product-add', 'productManage@productAdd');

Route::post('/add-product-action', 'productManage@addProductAction');

Route::get('/product-edit/{product_id}', 'productManage@productEdit');

Route::post('/product-update/{product_id}', 'productManage@productUpdate');

Route::get('/display-product/{product_id}', 'productManage@displayProduct');

Route::get('/hide-product/{product_id}', 'productManage@hideProduct');

Route::get('/product-delete/{product_id}', 'productManage@productDel');

// =================================order-manage=================================
Route::get('/order-list', 'orderManage@orderList');

Route::get('/order-waiting', 'orderManage@orderWaiting');

Route::get('/order-approve', 'orderManage@orderApproveAction');

Route::get('/order-detail/{order_id}', 'orderManage@orderDetail');

Route::get('/order-cancel', 'orderManage@orderCancel');

// =================================user-manage=================================
Route::get('/admin-user-manage/{admin_id}', 'userManage@adminUserManage');

Route::post('/admin-user-update/{admin_id}', 'userManage@adminUserUpdate');

Route::get('/client-user-manage', 'userManage@clientUserManage');

Route::get('/active-client/{client_id}', 'userManage@activeClient');

Route::get('/unactive-client/{client_id}', 'userManage@unactiveClient');

Route::get('/client-delete/{client_id}', 'userManage@clientDel');

// =================================send-mail=================================
Route::get('/send-mail', 'mailController@sendMail');

// =================================login-facebook=================================
Route::get('/login-facebook', 'homeController@loginFacebook');
Route::get('/client-login-fb/callback', 'homeController@callbackFacebook');

// =================================login-google=================================
Route::get('/login-google', 'homeController@loginGoogle');
Route::get('/client-login-gg/callback', 'homeController@callbackGoogle');
