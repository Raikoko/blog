<?php


namespace App\Http\Controllers\Common;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class WechatController extends Controller

{

    public function index(){
        return view('admin.wechat');
    }



    /**
     * 请求微信接口
     * @return mixed
     */
    public function oauth(){
        return Socialite::with('weixin')->redirect();
    }


    /**
     * 微信接口回调
     */
    public function callback(){
//        $user_data = Socialite::with('weixin')->user();
//        request()->session()->put('state',Str::random(40));
        $user_data = Socialite::driver('weixin')->stateless()->user();

        if (!isset($user_data->user)){
            return $this->error(1,'获取微信信息失败');
        }
        $user_info = User::getUserByOpen($user_data->user['openid']);
        if (empty($user_info)){
            User::createUserByOpen($user_data->user['nickname'],$user_data->user['openid']);
//            return $this->error(1,'用户不存在');
        }
        //生成token，跳转到后台首页
        $data['token'] = self::createToken($user_info);
        $data['user_info']['username'] = $user_info['username'];
        $data['user_info']['role'] = $user_info['role'];
        //重定向并存储session
        return redirect()->to('/common/index')->with('user_info',$data);
//        return redirect()->to('/common/index')->header('Authorization','Bearer '.$data['token']);

    }

    public function test(){

        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature(){

        Log::info('开始');

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        Log::info('参数接收');
        Log::info('$signature:'.$signature);
        Log::info('$timestamp:'.$timestamp);
        Log::info('$nonce:'.$nonce);

        $token = "weixin";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        Log::info('加密信息');
        Log::info('$tmpStr:'.$tmpStr);

        if($tmpStr == $signature){
            Log::info('结束:true');
            return true;
        }else{
            Log::info('结束；false');
            return false;
        }
    }
}
