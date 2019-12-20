<?php


namespace App\Models;



use Illuminate\Support\Facades\DB;

class Blog extends Model
{
    /**
     * 获取博客列表
     */
    public static function getBlogs($limit){
        return DB::table('blogs')->paginate($limit);
    }

    public static function getDetail($id){
        return DB::table('blogs')->where('id',$id)->first();
    }

    public static function create($title,$text){

        $time = date('Y-m-d H:i:s',time());
        return DB::table('blogs')->insert(['title'=>$title,'text'=>$text,'create_time'=>$time,'update_time'=>$time]);
    }

    public static function edit($id,$title,$text){
        $time = date('Y-m-d H:i:s',time());
        $data = ['title'=>$title,'text'=>$text,'update_time'=>$time];
        return $res = DB::table('blogs')->where('id',$id)->update($data);
    }

    public static function del($id){
        return $res = DB::table('blogs')->where('id',$id)->delete();
    }
}
