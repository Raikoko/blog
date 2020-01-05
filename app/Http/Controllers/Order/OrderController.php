<?php


namespace App\Http\Controllers\Order;


use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    /**
     * 生成订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request){
        $data = $request->input();

        $data = [
            'p_coupon_id'=>3,   //平台优惠卷ID
            'address_id'=>1,     //收货地址
            'goods_info'=>[     //按照店铺区分
                [
                    'item_info'=>[
                        [
                            'item_id'=>1, //商品ID
                            'num'=>2,     //商品数量
                            'cart_id'=>1, //购物车ID
                        ],
                        [
                            'item_id'=>2, //商品ID
                            'num'=>1,     //商品数量
                        ],
                    ],
                    'coupon_id'=>1,     //店铺优惠卷ID
                    'freight' =>10      //运费(按照店铺计算)
                ],
                [
                    'item_info'=>[
                        [
                            'item_id'=>3,
                            'num'=>2,
                        ]
                    ],
                    'coupon_id'=>'4',
                    'freight' =>0
                ],
            ]
        ];

        //创建订单，返回支付订单号
        $res = Order::createOrder($data);

        if ($res){
            return $this->success(0,$res);
        }
        return $this->error();

    }

    /**
     * 取消订单
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelOrder(){


        $res = true;
        if ($res){
            return $this->success();
        }
        return $this->error();

    }


}
