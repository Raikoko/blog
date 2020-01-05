<?php


namespace App\Models;

use \Illuminate\Database\Eloquent\Model as EloquentModel;
class Model extends EloquentModel
{
    //基类Model


    public static function getNowDateTime(){
        return date('Y-m-d H:i:s');
    }
}
