<?php


namespace App\Http\Controllers\Blog;


use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function index(){
        $blogs = Blog::getBlogs();
        return view('blog.index',['blogs'=>$blogs]);
    }


    public function create(){
        $title = request()->input('title');
        $text = request()->input('text');
        $data = request()->input();
        $rules = [
            'title' =>'required|unique:blogs|max:255',
            'body' =>'required'
        ];
//        var_dump($title);
//        var_dump($text);die;

//        $validator = Validator::make($data,$rules);
//        if ($validator->fails()) {
//            return ['code'=>1,'msg'=>'验证失败'];
//        }
        $res = Blog::create($title,$text);

        if ($res){
            return ['code'=>0,'msg'=>'成功'];
        }
        return ['code'=>1,'msg'=>'失败'];

    }


    public function detail($id){
        $blog = Blog::getDetail($id);
        return view('blog.detail',['blog'=>$blog]);
    }

    public function edit(){
        $title = request()->input('title');
        $text = request()->input('text');
        $id = request()->input('id');
        $res = Blog::edit($id,$title,$text);
        if ($res){
            return redirect('blog2');
        }
    }

    public function del($id){
        $res = Blog::del($id);
        if ($res){
            return redirect('blog2');
        }
    }
}
