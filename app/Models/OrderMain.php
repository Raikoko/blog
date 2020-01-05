<?php


namespace App\Models;


class OrderMain extends Model
{

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //可写入参数
    protected $fillable = [
        'pay_order_no',
        'amount_origin',
        'amount_finish',
        'p_coupon_minus',
        's_coupon_minus',
        'pay_status',
    ];


    /**
     * 创建主订单
     * @param $data
     * @return mixed
     */
    public static function createOrderMain($data){
        $insert = [
            'pay_order_no'=>$data['pay_order_no'],
            'amount_origin'=>$data['amount_origin'],
            'amount_finish'=>$data['amount_finish'],
            'p_coupon_minus'=>$data['p_coupon_minus'],
            's_coupon_minus'=>$data['s_coupon_minus'],
            'pay_status' => Order::PayWait
        ];

        return OrderMain::create($insert);
    }


    /**
     * 获取支付订单信息
     * @param $pay_order_no
     * @return mixed
     */
    public static function getOrderByPayNo($pay_order_no){
        return OrderMain::where('pay_order_no','=',$pay_order_no)->first();
    }
}
