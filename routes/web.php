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

Route::get('/blog2','blog\BlogController@index');

Route::get('/blog_detail/{id}','blog\BlogController@detail');

Route::get('/blog_create_form',function (){
    return view('blog.blog_form');
});

Route::post('/blog_create','blog\BlogController@create');

Route::get('/blog_edit_form/{blog}',function ($blog){
    return view('blog.blog_form',['blog'=>$blog]);
});

Route::post('/blog_edit','blog\BlogController@edit');
Route::get('/blog_del/{id}','blog\BlogController@del');



