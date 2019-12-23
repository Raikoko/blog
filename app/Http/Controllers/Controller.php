<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Mews\Captcha\Facades\Captcha;
use Tymon\JWTAuth\Facades\JWTAuth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * 获取token
     */
    public static function getToken($username,$password){

        $token= self::checkTokenExist($username);
        if (!empty($token)){
            return $token;
        }
        return self::createToken($username);
    }

    public static function checkTokenExist($username){
        return User::where('username',$username)->value('remember_token');
    }

    /**
     * 创建token
     */
    public static function createToken($username){

        $user_info = User::getUserByName($username);
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



}
