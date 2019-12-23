<?php
/**
 * Created by PhpStorm.
 * User: aa
 * Date: 2019/12/22
 * Time: 18:33
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;

class AdminController extends Controller
{

    /**
     * 登录首页
     */
    public function index(){

        if (request()->input()){
            return self::login_check();
        }
        return view('admin.login');
    }

    /**
     * 登录验证
     */
    public static function login_check(){
            $data = request()->input();

    }

    /**
     * 注册页面
     */
    public function register_index(){
        return view('admin.register');
    }

    public function register(){
        $data = request()->input();
        if (empty($data)){
            return ['code'=>1,'msg'=>'参数错误'];
        }
        $check_res =  self::check($data['account']);
        if (!$check_res){
            return ['code'=>1,'msg'=>'用户名已存在'];
        }

        $res = User::create($data['account'],$data['password']);
        if ($res){
            return ['code'=>0,'msg'=>'注册成功'];
        }
        return ['code'=>1,'msg'=>'注册失败'];
    }


    /**
     * 注册验证
     */
    public function register_check(){

        $username = request()->input('user');
        //信息校验
        $res =  self::check($username);
        if ($res){
            return ['code'=>0,'msg'=>'验证成功'];
        }
        return ['code'=>1,'msg'=>'验证失败'];

    }

    public static function check($username){
        $check_username_res = self::check_username($username);

        //校验通过
        if ($check_username_res){
            return true;
        }
        return false;
    }


    public static function check_username($username){
        //判断用户名是否存在
        if (empty($username)){
            return false;
        }
        $user_info = User::getUserByName($username);
        if ($user_info){
            return false;
        }
        return true;
    }

}