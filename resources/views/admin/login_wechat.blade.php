

@extends('layouts.base')

@section('title')
    登录页
@endsection
@section('content')
    <body>
    <div class="login-main">
        <header class="layui-elip">微信登录</header>
        <form class="layui-form">
            {{csrf_field()}}
            <div class="layui-input-inline">
                <input type="text" id="phone" name="phone" required lay-verify="required" placeholder="手机号" autocomplete="off"
                       class="layui-input">
            </div>
            <div class="layui-input-inline login-btn">
                <button lay-submit lay-filter="login" class="layui-btn">登录</button>
            </div>
            <input class="layui-input" type="hidden" name="type" required lay-verify="required" autocomplete="off" value="phone">
            <hr/>
            <p><a href="{{url('admin/register_index')}}" class="fl">立即注册</a><a href="javascript:;" class="fr">忘记密码？</a></p>
        </form>
    </div>
    </body>
    <script>
        layui.use(['layer','form'],function () {
            let layer = layui.layer;
            let form = layui.form;
            let $ = layui.$;

            form.on('submit(login)', function(data){
                $.post('/admin/do_login',data.field,function (res) {
                    layer.msg(res.msg);
                    if (res.code == 0){
                        layui.data('user_info',null);
                        layui.data('user_info',{
                            key:'data',
                            value:res.data
                        });
                        setTimeout(function () {
                            window.location.href = '{{url('admin/index')}}';
                        },2000);
                    }
                });
                return false;
            });


        });



    </script>
@endsection



