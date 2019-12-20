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
});


Route::get('/blog_detail/{id}','blog\BlogController@detail');
Route::get('/blog_create_form',function (){
    return view('blog.blog_form');
});


Route::get('/blog2',function (){return view('blog.index');});   //博客页面
Route::get('/blog/getIndex','blog\BlogController@getIndex'); //获取博客列表
Route::post('/blog_create','blog\BlogController@create');   //创建
Route::post('/blog/blog_edit','blog\BlogController@edit');  //编辑博客
Route::post('/blog/blog_del','blog\BlogController@del');    //删除博客



