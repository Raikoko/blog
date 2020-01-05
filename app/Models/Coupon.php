<?php


namespace App\Models;


class Coupon extends Model
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    const coupon_type_plat = 1;
    const coupon_type_shop = 0;

    /**
     * 根据ID获取优惠卷信息
     * @param $coupon_id
     * @param int $type
     * @return mixed
     */
    public static function getCouponById($coupon_id,$type=self::coupon_type_shop){
        return Coupon::where('id',$coupon_id)->where('type','=',$type)->first();
    }


    public static function checkCoupon($coupon_info,$uid,$item_ids){

        //优惠卷是否过期
        $check_out_time = self::checkCouponOutTime($coupon_info);
        if ($check_out_time){
            return false;
        }

        //该订单下的所有商品是否能使用优惠卷
        $check_item_is_can_use = self::checkItemIsCanUse($coupon_info,$item_ids);
        if (!$check_item_is_can_use){
            return false;
        }

        //该用户是否可用此优惠卷
        $check_user_is_can_use = self::checkUserIsCanUse($coupon_info,$uid);
        if (!$check_user_is_can_use){
            return false;
        }
        return true;
    }

    /**
     * 判断优惠卷是否过期，过期返回true
     * @param $coupon_info
     * @return bool
     */
    public static function checkCouponOutTime($coupon_info){
//        return true;
        return false;
    }

    /**
     * 判断商品是否可用该优惠卷
     * @param $coupon_info
     * @param $item_ids
     * @return bool
     */
    public static function checkItemIsCanUse($coupon_info,$item_ids){
        return true;
    }

    /**
     * 判断用户是否可用该优惠卷
     * @param $coupon_info
     * @param $uid
     * @return bool
     */
    public static function checkUserIsCanUse($coupon_info,$uid){

        //是否有该优惠卷


        //是否被使用

        return true;
    }

    /**
     * 判断订单金额是否可用满减
     * @param $coupon_info
     * @param $amount
     * @return bool
     */
    public static function checkIsMinus($coupon_info,$amount){

        if ($coupon_info['full'] <= $amount){
            return true;
        }
        return false;
    }

}
