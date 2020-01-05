<?php


namespace App\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function GuzzleHttp\Promise\all;

class Order extends Model
{
    //订单的支付状态
    const PayWait = 0;   //待支付
    const PaySuccess = 1;   //支付成功
    const PayError = 2;   //支付失败
    const PayClose = 3;   //支付关闭

    //订单状态
    const OrderSuccess = 1;     //订单支付成功状态
    const OrderWait = 0;     //等待支付


    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * 创建订单
     */
    public static function createOrder($data){

        $uid = 1;   //用户ID
        foreach ($data['goods_info'] as $k=>$order){
            $amount = '0.00';
            foreach ($order['item_info'] as $i=>$item){
                //校验商品信息
                $item_info = Item::getItemById($item['item_id']);
                if (!$item_info){
                    return ['code'=>1,'msg'=>'商品不存在'];
                }
                //判断库存是否足够
                $check_inventory = self::checkItemInventory($item_info,$item['num']);
                if (!$check_inventory){
                    return ['code'=>1,'msg'=>'商品库存不足'];
                }
                if (!isset($data['goods_info'][$k]['shop_id'])){
                    $data['goods_info'][$k]['shop_id']= $item_info['shop_id'];
                }
                $item_amount = self::getItemAmount($item_info['price'],$item['num']);
                $data['goods_info'][$k]['item_info'][$i]['item_amount'] = $item_amount;
                $data['goods_info'][$k]['item_info'][$i]['item_detail'] = $item_info;
                //单个订单,不计算优惠卷等的金额
                $amount = bcadd($amount,$item_amount,2);
            }

            $data['goods_info'][$k]['amount_origin'] = $amount;     //原始金额

            //按照店铺，处理店铺优惠卷
            $data['goods_info'][$k]['coupon_minus'] = '0.00';   //默认优惠卷满减0.00
           if (isset($order['coupon_id']) && !empty($order['coupon_id'])){
                $coupon_info = Coupon::getCouponById($order['coupon_id']);
                if (empty($coupon_info)){
                    return ['code'=>1,'msg'=>'优惠卷不正确'];
                }
                //检测优惠卷是否可用
                $item_ids = array_column($order['item_info'],'item_id');    //子订单下的所有商品ID
                $check_coupon = Coupon::checkCoupon($coupon_info,$uid,$item_ids);
                if (!$check_coupon){
                    return ['code'=>1,'msg'=>'优惠卷不能使用'];
                }
                //该订单是否符合优惠卷的满减条件
                $check_minus = Coupon::checkIsMinus($coupon_info,$amount);
                if ($check_minus){
                    //优惠卷满减可用修改coupon_minus
                    $data['goods_info'][$k]['coupon_minus'] = $coupon_info['minus'];
                }
//               $data['goods_info'][$k]['coupon_detail'] = $coupon_info;     //优惠卷信息
               $data['goods_info'][$k]['item_ids'] = $item_ids;     //商品id

           }

        }

        //计算总订单金额，并计算优惠卷运费等
        $data['sum_amount_origin'] = array_sum(array_column($data['goods_info'],'amount_origin'));  //总订单原始金额

        //处理平台优惠卷     算上运费后，进行平台优惠卷优惠处理
        //检测平台优惠卷是否可用
        $data['p_coupon_minus'] = '0.00';
        if (isset($data['p_coupon_id']) && !empty($data['p_coupon_id'])){
            $coupon_info = Coupon::getCouponById($data['p_coupon_id'],Coupon::coupon_type_plat);
            if (empty($coupon_info)){
                return ['code'=>1,'msg'=>'平台优惠卷不正确'];
            }
            $item_ids_all = array_column($data['goods_info'],'item_ids');    //总订单下的所有商品id
            $check_coupon = Coupon::checkCoupon($coupon_info,$uid,$item_ids_all);
            if (!$check_coupon){
                return ['code'=>1,'msg'=>'优惠卷不能使用'];
            }
            //总订单是否符合平台优惠卷的满减条件
            $check_minus = Coupon::checkIsMinus($coupon_info,$data['sum_amount_origin']);
            if ($check_minus){
                //优惠卷满减可用修改coupon_minus
                $data['p_coupon_minus'] = $coupon_info['minus'];
            }
//            $data['p_coupon_detail'] = $coupon_info;     //优惠卷信息
        }

        //总订单的最终价格  算运费并减去优惠卷的价格
        $data['sum_amount_finish'] = '0.01';
        $all_coupon_minus = array_sum(array_column($data['goods_info'],'coupon_minus'));//所有店铺优惠卷的满减金额
        $data['s_coupon_minus'] = $all_coupon_minus;
        $all_minus =  bcadd($all_coupon_minus,$data['p_coupon_minus'],2); //所有优惠卷的满减金额
        //总订单原始金额大于优惠卷满减金额则相减，不大于则默认支付0.01
        if (bccomp($data['sum_amount_origin'],$all_minus,2)){
            $data['sum_amount_finish'] = bcsub($data['sum_amount_origin'],$all_minus,2);
        }
        //加运费后的总订单金额
        $freight_all = array_sum(array_column($data['goods_info'],'freight'));
        $data['sum_amount_finish_freight'] = bcadd($data['sum_amount_finish'],$freight_all,2);

        //计算每个子订单，每个商品所占金额
        $sum_amount_origin_pc = '0.00';     //已计算商品金额
        $order_count = count($data['goods_info']);
        $data['sum_amount_origin_pc'] = bcsub($data['sum_amount_origin'],$data['p_coupon_minus'],2);//减去平台优惠卷后的总订单金额
        foreach ($data['goods_info'] as $key=>$order_d){

            //子订单占总订单的比例  算平台优惠卷优惠金额后所占的金额
            $rate_order = bcdiv($order_d['amount_origin'],$data['sum_amount_origin'],4);
            //最后一个子订单，总金额减去已计算金额
            if (bccomp($order_count,$key+1) == '0'){
                //最后一个进行相减
                $amount_origin_finish = bcsub($data['sum_amount_origin_pc'],$sum_amount_origin_pc,2);
            }else{
                $amount_origin_finish = bcmul($data['sum_amount_origin_pc'],$rate_order,2);
            }
            $sum_amount_origin_pc = bcadd($sum_amount_origin_pc,$amount_origin_finish,2);
            $data['goods_info'][$key]['amount_origin_finish'] = $amount_origin_finish;

            $sum_item_amount_c = '0.00';
            $item_count = count($order_d['item_info']);
            $amount_origin_finish_c = bcsub($amount_origin_finish,$order_d['coupon_minus'],2);//减去店铺优惠卷后的商品总金额
            $data['goods_info'][$key]['amount_origin_finish_c'] = $amount_origin_finish_c;
            foreach ($order_d['item_info'] as $i_d=>$item_d){
                //商品原价占子订单的比例   算店铺优惠卷优惠金额
                $rate_item = bcdiv($item_d['item_amount'],$order_d['amount_origin'],4);
                if (bccomp($item_count,$i_d+1) == '0'){
                    $item_amount_finish = bcsub($amount_origin_finish_c,$sum_item_amount_c,2);
                }else{
                    $item_amount_finish = bcmul($amount_origin_finish_c,$rate_item,2);
                }
                $sum_item_amount_c = bcadd($sum_item_amount_c,$item_amount_finish,2);
                $data['goods_info'][$key]['item_info'][$i_d]['item_amount_finish'] = $item_amount_finish;
            }
        }

        //处理完成，保存订单信息
        return self::saveOrderInfo($data);

    }

    /**
     * 保存订单信息，并生成支付订单
     * @param $data
     * @return bool|false|string
     */
    public static function saveOrderInfo($data){

        DB::beginTransaction();
        try {
            //创建主订单 order_main
            $pay_order_no = self::generateOrderNo();
            $main_data = [
                'pay_order_no'=>$pay_order_no,
                'amount_origin'=>$data['sum_amount_origin'],
                'amount_finish'=>$data['sum_amount_finish'],
                'p_coupon_minus'=>$data['p_coupon_minus'],
                's_coupon_minus'=>$data['s_coupon_minus'],
            ];

            $order_main = OrderMain::createOrderMain($main_data);

            //循环创建子订单   按照店铺
            $orders = [];
            foreach ($data['goods_info'] as $k=>$order){
                //生成订单号
                $order_no = self::generateOrderNo();
                //判断订单号是否存在
                $check_order_no = self::getOrderByNo($order_no);
                if (!empty($check_order_no)){
                    return false;
                }
                //生成子订单
                $orders[] = [
                    'order_no'=>$order_no,
                    'order_main_id'=>$order_main->id,
                    'order_status'=>self::OrderWait,
                    'amount_origin'=>$order['amount_origin'],
                    'amount_finish'=>$order['amount_origin_finish_c'],
                    'create_time'=>self::getNowDateTime(),
                    'update_time'=>self::getNowDateTime(),
                ];
            }
            //批量添加子订单
            $add_res = DB::table('orders')->insert($orders);

            //返回最后的订单信息
            if (!$add_res){
                return false;
            }
            DB::commit();
            return $pay_order_no;

        }catch (\Exception $exception){
            DB::rollBack();
            return false;
        }


    }


    /**
     * 检测商品库存
     * @param $item_info
     * @param $num
     * @return bool
     */
    public static function checkItemInventory($item_info,$num){

        //从redis中读取商品库存信息

        //库存是否足够
        if ($item_info['inventory'] < $num){
            return  false;
        }
        return true;
    }

    /**
     * 获取某个商品金额
     * @param $item_price
     * @param $item_num
     * @return float|int
     */
    public static function getItemAmount($item_price,$item_num){
        return bcmul($item_price,$item_num,2);
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
    public static function changeOrderStatusSuccess($pay_order_no,$trade_no){

        $order_main_info = OrderMain::getOrderByPayNo($pay_order_no);
        if (empty($order_main_info)){
            return ['code'=>1,'msg'=>'订单不存在'];
        }

        //判断订单状态是否已经修改为支付成功,未修改则修改为支付成功
        if ($order_main_info->pay_status == self::PaySuccess){
            return ['code'=>0,'msg'=>'修改成功'];
        }
        $order_main_info->pay_status = self::PaySuccess;
        $order_main_info->zf_pay_no = $trade_no;    //支付宝订单号
        if (!$order_main_info->save()){
            return ['code'=>1,'msg'=>'修改失败'];
        }
        Log::info('主订单修改成功');
        //修改子订单状态
        $res = Order::where('order_main_id','=',$order_main_info['id'])->update(['order_status'=>self::OrderSuccess]);
        Log::info('修改子订单结果'.$res);
        if (!$res){
            return ['code'=>1,'msg'=>'修改失败'];
        }
        Log::info('子订单修改成功');
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

    /**
     *生成订单号
     */
    public static function generateOrderNo(){
        //16位日期 + 8位随机数
       return date('YmdHis'.mt_rand(10000000,99999999));
    }

}
