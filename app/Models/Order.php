<?php


namespace App\Models;


use Illuminate\Support\Facades\Log;

class Order extends Model
{
    const PayError = 0;   //未支付成功
    const PaySuccess = 1;   //支付成功
    const OrderSuccess = 1;     //订单支付成功状态


    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * 创建订单
     */
    public static function createOrder(){

    }

    /**
     * 根据订单号获取订单信息
     * @param $order_no
     * @return mixed
     */
    public static function getOrderByNo($order_no){
       return Order::where('order_no','=',$order_no)->first();
    }

    /**
     * 修改订单状态为成功
     */
    public static function changeOrderStatusSuccess($order_no,$trade_no){
        $order_info = Order::getOrderByNo($order_no);
        if (empty($order_info)){
            return ['code'=>1,'msg'=>'订单不存在'];
        }

        //判断订单状态是否为支付成功,不等于则修改订单状态为支付成功
        if ($order_info->pay_status == self::PaySuccess && $order_info->order_status == self::OrderSuccess){
            return ['code'=>0,'msg'=>'修改成功'];
        }
        $order_info->order_status = self::OrderSuccess;
        $order_info->pay_status = self::PaySuccess;
        Log::info('支付宝订单号修改部分（异步）:'.$trade_no);

        $order_info->pay_no = $trade_no;    //支付宝订单号

        if (!$order_info->save()){
            return ['code'=>1,'msg'=>'修改失败'];
        }
        return ['code'=>0,'msg'=>'修改成功'];
    }


    /**
     * 修改订单状态
     * @param $order_no
     * @param $status
     * @return array
     */
    public static function changeOrderStatus($order_no,$status){
        $order_info = Order::getOrderByNo($order_no);
        if (empty($order_info)){
            return ['code'=>1,'msg'=>'订单不存在'];
        }
        $order_info->order_status = $status;
        if ($order_info->save()){
            return ['code'=>0,'msg'=>'修改成功'];
        }
        return ['code'=>1,'msg'=>'修改失败'];
    }

    /**
     * 修改订单支付状态
     * @param $order_no
     * @param $status
     * @return array
     */
    public static function changeOrderPayStatus($order_no,$status){
        $order_info = Order::getOrderByNo($order_no);
        if (empty($order_info)){
            return ['code'=>1,'msg'=>'订单不存在'];
        }
        $order_info->pay_status = $status;
        if ($order_info->save()){
            return ['code'=>0,'msg'=>'修改成功'];
        }
        return ['code'=>1,'msg'=>'修改失败'];
    }

}
