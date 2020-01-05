<?php


namespace App\Models;


class Item extends Model
{
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';


    /**
     *根据ID获取商品信息
     * @param $item_id
     * @return mixed
     */
    public static function getItemById($item_id){
        return Item::where('id',$item_id)->first();
    }
}
