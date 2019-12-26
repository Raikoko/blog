<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class MessageController extends Controller
{


    /**
     * 发送短信验证码统一入口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request){


        //调试模式不发送验证码
        if (config('debug.debug')){
            return $this->success();
        }
        $phone_num = $request->input('phone');
        $check_phone = self::checkPhone($phone_num);
        if (!$check_phone){
            $this->error(1,'手机号不正确');
        }

        //生成验证码并发送
        $code = self::getRandCode(6);
        $send_res = self::sendMsg($code);
        if (!$send_res){
            return $this->error(1,'发送失败');
        }
        //发送成功存入redis
        $save_res = self::saveCodeRedis($phone_num,$code);
//        if (!$save_res){
//            return $this->error(1,'发送失败');
//        }
        return $this->success();

    }

    /**
     * 发送验证码
     * @param $code
     * @return bool
     */
    public static function sendMsg($code){
        return true;
    }

    /**
     * 检测手机号
     * @param $phone
     * @return bool
     */
    public static function checkPhone($phone){
        return true;
    }

    /**
     * 获取随机验证码
     * @param int $length
     * @return string
     */
    public static function getRandCode($length = 6){
        $chars = ['0','1','2','3','4','5','6','7','8','9'];
        $chars_len = count($chars) -1;
        shuffle($chars);
        $output = "";
        for ($i=0;$i<$length;$i++){
            $output .=$chars[mt_rand(0,$chars_len)];
        }
        return $output;
    }


    /**
     * 从redis获取code
     * @param $phone
     * @return mixed
     */
    public static function getCode($phone){
        $key = self::getCodeKey($phone);
        return Redis::get($key);
    }

    /**
     * 保存验证码
     * @param $phone
     * @param $code
     * @return mixed
     */
    public static function saveCodeRedis($phone,$code){
        $key = self::getCodeKey($phone);
        $res = Redis::setex($key,config('debug.sms_valid_time'),$code);
        return $res;
    }


    /**
     * 获取验证码对应的key
     * @param $phone
     * @return string
     */
    public static function getCodeKey($phone){
        return 'phone_'.$phone;
    }



}
