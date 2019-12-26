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











