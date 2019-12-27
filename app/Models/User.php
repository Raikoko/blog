<?php
/**
 * Created by PhpStorm.
 * User: aa
 * Date: 2019/12/22
 * Time: 20:36
 */

namespace App\Models;


use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [];
    }


    const role_admin = 1;   //管理员


    public static function create($username,$password){
        $time = self::getNowDateTime();
        $password_encode = self::encode_password($password);

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
    public static function encode_password($password){
        return Hash::make($password);
    }

    /**
     * 用户名获取用户
     * @param $username
     * @return mixed
     */
    public static function getUserByName($username){
        return User::where('username',$username)->first();
    }

    /**
     * 手机号获取用户
     * @param $phone
     * @return mixed
     */
    public static function getUserByPhone($phone){
        return User::where('phone',$phone)->first();
    }


    public static function getUserByEmail($email){
        return User::where('email',$email)->first();
    }


    public static function getNowDateTime(){
        return self::getDateTime(time());
    }

    /**
     * 获取格式化的时间
     * @param $time
     * @return false|string
     */
    public static function getDateTime($time){
        return date('Y-m-d H:i:s',$time);
    }


}
