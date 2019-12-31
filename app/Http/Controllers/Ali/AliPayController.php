<?php


namespace App\Http\Controllers\Ali;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yansongda\Pay\Pay;

class AliPayController extends Controller
{

    public function payIndex(){
        return view('ali.pay_index');
    }

    /**
     * 支付成功后的回调页面
     */
    public function paySuccess(Request $request){
        $data = $request->input();
        Log::info('支付成功后的信息(同步)'.json_encode($data));
    }

    /**
     * 支付成功后的通知
     */
    public function payNotify(Request $request){
        $data = $request->input();
        $id = $request->input('id');
        $request_id = $request->id;

        Log::info('支付成功后的id（异步）:'.$id);

        Log::info('支付成功后的request_id（异步）:'.$request_id);

        Log::info('支付成功后的信息（异步）'.json_encode($data));

    }


    /**
     * 手机网页支付接口
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aliPay(Request $request){
        $aliPayOder = [
            'out_trade_no' => time(),
            'total_amount' => 0.01,    //支付金额
            'subject' => '支付宝手机网页支付'    //备注
        ];

        $config = config('alipay.pay');

        $config['return_url'] = $config['return_url'].'?id='.$request->id;

        $config['notify_url'] = $config['notify_url'].'?id='.$request->id;

        return Pay::alipay($config)->wap($aliPayOder);
    }


    /**
     * 扫码支付
     * @param Request $request
     * @return bool
     */
    public function aliPayScan(Request $request){

        $id = $request->input('id');

        $aliPayOder = [
            'out_trade_no' => time(),
            'total_amount' => 0.01,    //支付金额
            'subject' => '支付宝扫码支付'    //备注
        ];
        $config = config('alipay.pay');

        $config['return_url'] = $config['return_url'].'?id='.$id;

        $scan = Pay::alipay($config)->scan($aliPayOder);

        if (empty($scan->code) || $scan->code !== '10000') return false;

        $url = $scan->qr_code.'?id'.$id;

        //生成二维码
        return QrCode::encoding('UTF-8')->size(300)->generate($url);

    }


    /**
     * APP支付接口
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aliPayApp(Request $request){
        $id = $request->input('id');

        $aliPayOder = [
            'out_trade_no' => time(),
            'total_amount' => 0.01,    //支付金额
            'subject' => '支付宝扫码支付'    //备注
        ];
        $config = config('alipay.pay');

        $config['return_url'] = $config['return_url'].'?id='.$id;

        return Pay::alipay($config)->app($aliPayOder);
    }

    public function aliPayRefund(Request $request){
        try{
            $payOrder = [
                'out_trade_no'=> '0.01',    //商家订单号
                'refund_amount'=> '0.01',   //退款金额  不得超过该订单的总金额
                'out_request_no'=> ''       //同一笔交易多次退款标识（部分退款标识）
            ];

            $config = config('alipay.pay');

            $result = Pay::alipay($config)->refund($payOrder);
            if (empty($result->code || $result->code !=='10000')){
                throw new \Exception('请求支付宝退款接口失败');
            }

            //退款成功  修改订单状态



        }catch (\Exception $exception){
            Log::error($exception->getMessage());
            return false;
        }
    }
}
