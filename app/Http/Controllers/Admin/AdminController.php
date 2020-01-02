<?php
/**
 * Created by PhpStorm.
 * User: aa
 * Date: 2019/12/22
 * Time: 18:33
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Controllers\MessageController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use mysql_xdevapi\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use function Psy\debug;

class AdminController extends Controller
{
    /**
     * 登录首页
     */
    public function index(){
        return view('admin.login');
    }

    public function admin_index(){
        return view('admin.index');
    }

    /**
     * 登录
     */
    public function login(){

        $username = request()->input('account');
        $password = request()->input('password');
        $code = request()->input('code');
        $code_key = request()->input('code_key');

        $login_type= request()->input('type');
        $phone= request()->input('phone');

        //手机号登录
        switch ($login_type){
            case 'phone': return self::login_phone($phone,$code);
            case 'wechat':return self::login_wechat();
        }

        $check_code = self::check_code($code,$code_key);
        if (!$check_code) {
            return $this->error('1','验证码不正确');
        }

        $login_type = config('debug.login_type');
        if ($login_type == login_check_normal){
            return self::login_normal($username,$password);
        }
        if ($login_type == login_check_auth){
            $check_data = ['username'=>$username,'password'=>$password];
            return self::login_auth($check_data);
        }

    }

    /**
     * 微信登录
     */
    public static function login_wechat(){

        return true;
    }




    /**
     * 手机验证码登录
     * @param $phone
     * @param $code
     * @return bool
     */
    public static function login_phone($phone,$code){

        $code_redis = MessageController::getCode($phone);
        //调试模式默认验证码为 666666
        if (config('debug.debug')){
            $code_redis= 666666;
        }
        if ($code != $code_redis){
            return response()->json(['code'=>1,'msg'=>'验证码不正确']);
        }

        //生成token，返回用户信息
        $user_info = User::getUserByPhone($phone);
        if (empty($user_info)){
            return response()->json(['code'=>1,'msg'=>'手机号不存在']);
        }
        $data['token'] = self::createToken($user_info);
        $data['user_info']['username'] = $user_info['username'];
        $data['user_info']['role'] = $user_info['role'];

        return response()->json(['code'=>0,'msg'=>'success','data'=>$data]);

    }

    /**
     * 普通登录
     */
    public static function login_normal($username,$password){
        $check_res = self::login_check($username,$password);
        if ($check_res['code'] == 0){
            //生成token，返回用户信息
            $data['token'] = self::getToken($username,$password);
            $user_info = User::getUserByName($username);
            $data['user_info']['username'] = $user_info['username'];
            $data['user_info']['role'] = $user_info['role'];
            return ['code'=>0,'msg'=>'success','data'=>$data];
        }
        return $check_res;
    }


    /**
     * auth验证登录
     */
    public static function login_auth($check_data){
        if (! $token = auth('api')->attempt($check_data)){
            return response()->json(['code'=>1,'msg'=>'error']);
        }
        $data['token'] = $token;
        $user_info = User::getUserByName($check_data['username']);
        $data['user_info']['username'] = $user_info['username'];
        $data['user_info']['role'] = $user_info['role'];
        return response()->json(['code'=>0,'msg'=>'success','data'=>$data]);
    }

    /**
     * 退出
     */
    public function logout(){
        auth('api')->logout();
        return ['code'=>0,'msg'=>'成功'];
    }


    /**
     * 找回密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request){

        $data['password'] = $request->input('password');
        $data['password_confirmation'] = $request->input('repassword');
        //密码校验
        $rules = [
            'password'=>'required|between:6,7|confirmed',
            'password_confirmation'=>'required|between:6,7',
        ];

        $messages = [
            'required'=>'密码不能为空',
            'between'=>'密码必须是6~20位之间',
            'confirmed'=>'输入密码不一致',
        ];

        $validator = Validator::make($data,$rules,$messages);
        if ($validator->fails()){
            return $this->error(1,$validator->errors()->first());
        }
        $user = auth('api')->user();
        $user->password = User::encode_password($data['password']);
        if ($user->save()){
            return $this->success();
        }
        return $this->error();
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
    public static function login_check($username,$password){

        $check_pwd = self::check_pwd($username,$password);
        if (!$check_pwd){
            return ['code'=>1,'msg'=>'用户名或密码不正确'];
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
        return Hash::check($password,$user_info['password']);
    }

    /**
     * 验证码验证
     * @param $code
     * @param $code_key
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
