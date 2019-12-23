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
        return view('admin.login');
    }

    /**
     * 登录
     */
    public function login(){
        $username = request()->input('account');
        $password = request()->input('password');
        $code = request()->input('code');
        $code_key = request()->input('code_key');

        $check_res = self::login_check($username,$password,$code,$code_key);

        if ($check_res['code'] == 0){
            //生成token，返回用户信息
            $data['token'] = self::getToken($username,$password);
            $user_info = User::getUserByName($username);
            $data['user_info']['username'] = $user_info['username'];
            $data['user_info']['role'] = $user_info['role'];
            return ['code'=>0,'msg'=>'登录成功','data'=>$data];
        }
        return $check_res;
    }

    /**
     * 退出
     */
    public function logout(){
        auth('api')->logout();
        return ['code'=>0,'msg'=>'成功'];
    }


    /**
     * 获取图片验证码
     */
    public function getCaptcha(){
        $img_url = self::getImgCode();
        return ['code'=>0,'msg'=>'成功','data'=>['img_url'=>$img_url]];
    }


    /**
     * 登录验证
     */
    public static function login_check($username,$password,$code,$code_key){

        $check_pwd = self::check_pwd($username,$password);
        $check_code = self::check_code($code,$code_key);
        if (!$check_pwd){
            return ['code'=>1,'msg'=>'用户名或密码不正确'];
        }
        if (!$check_code) {
            return ['code'=>1,'msg'=>'验证码不正确'];
        }
        return ['code'=>0,'msg'=>'成功'];
    }


    /**
     * 密码验证
     * @param $username
     * @param $password
     * @return bool
     */
    public static function check_pwd($username,$password){
        $user_info = User::getUserByName($username);
        if (empty($user_info)){
            return false;
        }
        $password_encode = User::encode_pasword($password);
        if (strcmp($user_info['password'],$password_encode) == 0){
            return true;
        }
        return false;
    }

    /**
     * 验证码验证
     * @param $code
     * @return bool
     */
    public static function check_code($code,$code_key){
      return captcha_api_check($code,$code_key) ? true : false;
    }

    /**
     * 注册页面
     */
    public function register_index(){
        return view('admin.register');
    }


    /**
     * 注册
     * @return array
     */
    public function register(){
        $data = request()->input();
        if (empty($data)){
            return ['code'=>1,'msg'=>'参数错误'];
        }
        $check_res =  self::check($data['account'],$data['email']);
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
     * 注册验证用户名是否存在
     */
    public function register_check(){

        $username = request()->input('user');
        //信息校验
        $res =  self::check_username($username);
        if ($res){
            return ['code'=>0,'msg'=>'验证成功'];
        }
        return ['code'=>1,'msg'=>'验证失败'];

    }


    /**
     * 注册验证
     * @param $username
     * @return bool
     */
    public static function check($username,$email){
        $check_username_res = self::check_username($username);
        $check_email_res = self::check_email($email);
        return $check_username_res && $check_email_res ? true : false;
    }


    /**
     * 判断用户名是否存在
     * @param $username
     * @return bool
     */
    public static function check_username($username){
        if (empty($username)){
            return false;
        }
        $user_info = User::getUserByName($username);
        return $user_info ?  false : true;
    }


    /**
     * 邮箱验证是否存在
     * @param $email
     * @return bool
     */
    public static function check_email($email){

        return true;

        if (empty($email)){
            return false;
        }
        $user_info = User::getUserByEmail($email);
        return $user_info ?  false : true;
    }

}
