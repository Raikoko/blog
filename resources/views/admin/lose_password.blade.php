

@extends('layouts.base')

@section('title')
    找回密码
@endsection
@section('content')
    <body>
    <div class="login-main">
        <header class="layui-elip">找回密码</header>
        <form class="layui-form">
            {{csrf_field()}}
            <div class="layui-input-inline">
                <input type="text" id="phone" name="phone" required lay-verify="required" placeholder="手机号" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="layui-inline" style="width: 70%;">
                    <input class="layui-input" type="text" name="code" required lay-verify="required" placeholder="验证码" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <button id="get_code" class="layui-btn layui-btn-sm" type="button">获取验证码</button>
                </div>
            </div>
            <div class="layui-input-inline login-btn">
                <button lay-submit lay-filter="login" class="layui-btn">找回密码</button>
            </div>
            <input class="layui-input" type="hidden" name="type" required lay-verify="required" autocomplete="off" value="phone">
            <hr/>
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
                        setTimeout(function () {
                            window.location.href = 'reset_password/'+res.data.token;
                        },2000);
                    }
                });
                return false;
            });

            $('#get_code').on('click',function () {
                let phone = $('#phone').val();
                $.post('/send_msg',{phone:phone},function (res) {
                    if (res.code == 0){
                        layer.msg('发送成功');
                    }else{
                        layer.msg('发送失败');
                    }
                });
            });
        });



    </script>
@endsection



