<?php
/**
 * Created by PhpStorm.
 * User: aa
 * Date: 2019/12/22
 * Time: 20:36
 */

namespace App\Models;


use Illuminate\Support\Facades\DB;

class User extends Model
{

    const role_admin = 1;   //管理员

    public static function create($username,$password){
        $time = self::getNowDateTime();
        $password_encode = self::encode_pasword($password);

        $insert = [
            'username'=>$username,
            'password'=>$password_encode,
            'role'=>self::role_admin,
            'create_time'=>$time,
            'update_time'=>$time
        ];
        return DB::table('users')->insert($insert);
    }

    /**
     * 密码加密
     * @param $password
     * @return string
     */
    public static function encode_pasword($password){
        return md5($password);
    }

    /**
     * 用户名获取用户
     * @param $username
     * @return mixed
     */
    public static function getUserByName($username){
        return User::where('username',$username)->first();
    }


    public static function getNowDateTime(){
        return self::getDateTime(time());
    }

    /**
     * 获取格式化的时间
     * @param int $time
     */
    public static function getDateTime($time){
        return date('Y-m-d H:i:s',$time);
    }


}