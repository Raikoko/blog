<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Mews\Captcha\Facades\Captcha;
use Tymon\JWTAuth\Facades\JWTAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct()
    {

        $request_url = request()->path();   //路由前不带'/'
        $whitelist_urls = config('whitelist.url');
        //不在白名单中，需要token验证
//        Log::info('请求路由:'.$request_url);
        if (!in_array($request_url,$whitelist_urls)){
            //token验证
//            $this->middleware('auth:api', ['except' => ['login']]);   //中间件
                //获取token
//            $token = request()->header('authorization');

                $token = auth('api')->getToken();
                if (is_null($token) || empty($token)){
                    throw new ApiException('token不存在','401');
                }
                if (!JWTAuth::parseToken()->check()){
                    throw new ApiException('token失效','401');
                }
                //获取用户信息
//                $user= auth('api')->user();
            $user = JWTAuth::parseToken()->toUser()->toArray();
                if(!$user){
                    throw new ApiException('用户不存在','401');
                }
        }
    }

    /**
     * token验证
     */
    public static function checkToken(){
        return JWTAuth::parseToken()->check();
    }


    /**
     * 获取token
     */
    public static function getToken($username,$password){

        $token= self::checkTokenExist($username);
        if (!empty($token)){
            return $token;
        }
        $user_info = User::getUserByName($username);
        return self::createToken($user_info);
    }

    public static function checkTokenExist($username){
        return User::where('username',$username)->value('remember_token');
    }

    /**
     * 创建token
     */
    public static function createToken($user_info){
        $token = JWTAuth::fromUser($user_info);
        return $token;
    }

    /**
     * 获取图片验证码
     */
    public static function getImgCode(){
//        return captcha_src();
        return Captcha::create('default',true);
    }


    /**
     * 统一接口返回，成功
     */
    public function success($code=0,$data=null,$msg='success'){
        return self::returnJson($code,$msg,$data);
    }


    /**
     * 统一接口返回，失败
     */
    public function error($code=9999,$msg='error'){
        return self::returnJson($code,$msg);
    }

    public static function returnJson($code,$msg,$data=null){
        $return = [
          'code'=>$code,
          'msg'=>$msg
        ];
        if (!is_null($data)){
            $return['data'] = $data;
        }
        return response()->json($return);
    }


}
