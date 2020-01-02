<?php

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

Route::get('/', function () {
    return view('welcome');
})->middleware('auth:api');


Route::get('/blog_detail/{id}','blog\BlogController@detail');
Route::get('/blog_create_form',function (){
    return view('blog.blog_form');
});


Route::get('/blog2',function (){return view('blog.index');})->middleware('auth:api');   //博客页面
Route::get('/blog/getIndex','blog\BlogController@getIndex'); //获取博客列表
Route::post('/blog_create','blog\BlogController@create');   //创建
Route::post('/blog/blog_edit','blog\BlogController@edit');  //编辑博客
Route::post('/blog/blog_del','blog\BlogController@del');    //删除博客

Route::get('/admin/login','admin\AdminController@index')->name('login');   //登录页面
Route::get('/admin/register_index','admin\AdminController@register_index');   //注册页面
Route::post('/admin/register_check','admin\AdminController@register_check');   //用户名验证
Route::post('/admin/register','admin\AdminController@register');   //注册
Route::post('/admin/do_login','admin\AdminController@login');   //登录
Route::post('/admin/logout','admin\AdminController@logout');   //退出登录
Route::get('/admin/index','admin\AdminController@admin_index');   //管理员后台首页

Route::get('/get_captcha','admin\AdminController@getCaptcha');   //获取验证码

Route::get('/admin/login_phone',function (){
    return view('admin.login_phone');
});   //手机号登录页面
Route::post('/send_msg','MessageController@send');   //发送短信验证码

Route::get('/admin/lose_password',function (){
    return view('admin.lose_password');
});   //找回密码页面
Route::get('/admin/reset_password/{token}',function (){
    return view('admin.reset_password');
});   //重新设置密码页面
Route::post('/admin/reset','admin\AdminController@resetPassword');   //重新设置密码


Route::get('/admin/login_wechat',function (){return view('admin.login_wechat');});   //微信登录页面

Route::get('/common/oauth','common\WechatController@oauth');   //请求微信接口
Route::get('/common/callback','common\WechatController@callback');   //微信接口回调地址
Route::get('/common/test','common\WechatController@test');   //测试微信接口
Route::get('/common/index','common\WechatController@index');   //测试微信页面


Route::get('/ali/pay_index','ali\AliPayController@payIndex');   //支付首页
Route::get('/ali/pay_success','ali\AliPayController@paySuccess');   //支付宝支付成功后的回调页面
Route::post('/ali/pay_notify','ali\AliPayController@payNotify');   //支付成功后的通知地址

Route::post('/ali/aliPay','ali\AliPayController@aliPay');   //网页支付
Route::post('/ali/aliPayScan','ali\AliPayController@aliPayScan');   //扫码支付










