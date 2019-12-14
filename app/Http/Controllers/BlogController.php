<?php


namespace App\Http\Controllers;


class BlogController extends Controller
{
    public function index(){
        var_dump('index,index2');
        return view('blog.index');
    }
}
