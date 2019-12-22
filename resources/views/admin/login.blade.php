<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>登录页</title>
    <link href="{{asset('layui/css/layui.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>

</head>

<body>

<div class="login-main">
    <header class="layui-elip">登录</header>
    <form class="layui-form">
        {{csrf_field()}}
        <div class="layui-input-inline">
            <input type="text" name="account" required lay-verify="required" placeholder="用户名" autocomplete="off"
                   class="layui-input">
        </div>
        <div class="layui-input-inline">
            <input type="password" name="password" required lay-verify="required" placeholder="密码" autocomplete="off"
                   class="layui-input">
        </div>

        <div class="layui-input-inline">
            <input type="text" name="code" required lay-verify="required" placeholder="验证码" autocomplete="off"
                   class="layui-input">
        </div>

        <div class="layui-input-inline login-btn">
            <button lay-submit lay-filter="login" class="layui-btn">登录</button>
        </div>
        <hr/>
        <!--<div class="layui-input-inline">
            <button type="button" class="layui-btn layui-btn-primary">QQ登录</button>
        </div>
        <div class="layui-input-inline">
            <button type="button" class="layui-btn layui-btn-normal">微信登录</button>
        </div>-->
        <p><a href="{{url('admin/register_index')}}" class="fl">立即注册</a><a href="javascript:;" class="fr">忘记密码？</a></p>
    </form>
</div>
</body>
<script>
    layui.use(['layer','table'],function () {
        let table = layui.table;
        let layer = layui.layer;
        let form = layui.form;
        let $ = layui.$;

        form.on('submit(login)', function(data){
            $.post('/admin/login',data.field,function (res) {
                layer.msg(res.msg,{time:2000});
            });
            layer.close(index);
            table.reload('table-blog');
            return false;
        });

    });

</script>

</html>

