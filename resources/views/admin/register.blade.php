<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    {{--<meta name="renderer" content="webkit">--}}
    {{--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">--}}
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>注册页</title>
    <link href="{{asset('layui/css/layui.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('layui/layui.js')}}"></script>
</head>
<body>

<div class="login-main">
    <header class="layui-elip" style="width: 82%">注册页</header>

    <!-- 表单选项 -->
    <form class="layui-form">
        <div class="layui-input-inline">
        {{csrf_field()}}
        <!-- 用户名 -->
            <div class="layui-inline" style="width: 85%">
                <input type="text" id="user" name="account" required  lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
            </div>
            <!-- 对号 -->
            <div class="layui-inline">
                <i class="layui-icon" id="ri" style="color: green;font-weight: bolder;" hidden></i>
            </div>
            <!-- 错号 -->
            <div class="layui-inline">
                <i class="layui-icon" id="wr" style="color: red; font-weight: bolder;" hidden>ဆ</i>
            </div>
        </div>
        <!-- 密码 -->
        <div class="layui-input-inline">
            <div class="layui-inline" style="width: 85%">
                <input type="password" id="pwd" name="password" required  lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
            <!-- 对号 -->
            <div class="layui-inline">
                <i class="layui-icon" id="pri" style="color: green;font-weight: bolder;" hidden></i>
            </div>
            <!-- 错号 -->
            <div class="layui-inline">
                <i class="layui-icon" id="pwr" style="color: red; font-weight: bolder;" hidden>ဆ</i>
            </div>
        </div>
        <!-- 确认密码 -->
        <div class="layui-input-inline">
            <div class="layui-inline" style="width: 85%">
                <input type="password" id="rpwd" name="repassword" required  lay-verify="required" placeholder="请确认密码" autocomplete="off" class="layui-input">
            </div>
            <!-- 对号 -->
            <div class="layui-inline">
                <i class="layui-icon" id="rpri" style="color: green;font-weight: bolder;" hidden></i>
            </div>
            <!-- 错号 -->
            <div class="layui-inline">
                <i class="layui-icon" id="rpwr" style="color: red; font-weight: bolder;" hidden>ဆ</i>
            </div>
        </div>


        <div class="layui-input-inline login-btn" style="width: 85%">
            <button type="submit" lay-submit lay-filter="register" class="layui-btn">注册</button>
        </div>
        <hr style="width: 85%" />
        <p style="width: 85%"><a href="{{url('/admin/login')}}" class="fl">已有账号？立即登录</a><a href="javascript:;" class="fr">忘记密码？</a></p>
    </form>
</div>

</body>

<script>
    layui.use(['layer','table'],function () {
        let table = layui.table;
        let layer = layui.layer;
        let form = layui.form;
        let $ = layui.$;


        //用户名校验
        $('#user').blur(function() {
            let user = $(this).val();
            if(user){
                $.post('/admin/register_check',{user:user,'_token':'{{csrf_token()}}'},function (res) {
                    if (res.code == 0) {
                        $('#ri').removeAttr('hidden');
                        $('#wr').attr('hidden','hidden');

                    } else {
                        $('#wr').removeAttr('hidden');
                        $('#ri').attr('hidden','hidden');
                        layer.msg('当前用户名已存在')
                    }
                });
            }
        });


        // 为密码添加正则验证
        $('#pwd').blur(function() {
            let reg = /^[\w]{6,12}$/;
            if(!($('#pwd').val().match(reg))){
                $('#pwr').removeAttr('hidden');
                $('#pri').attr('hidden','hidden');
                layer.msg('请输入合法密码');
            }else {
                $('#pri').removeAttr('hidden');
                $('#pwr').attr('hidden','hidden');
            }
        });

        //验证两次密码是否一致
        $('#rpwd').blur(function() {
            if($('#pwd').val() != $('#rpwd').val()){
                $('#rpwr').removeAttr('hidden');
                $('#rpri').attr('hidden','hidden');
                layer.msg('两次输入密码不一致!');
            }else {
                $('#rpri').removeAttr('hidden');
                $('#rpwr').attr('hidden','hidden');
            }
        });


        form.on('submit(register)', function(data){
            $.post('/admin/register',data.field,function (res) {
                layer.msg(res.msg);
                if(res.code == 0){
                   setTimeout(function () {
                       location.href = "{{url('admin/login')}}";
                   },3000);
                }
            });
            return false;
        });

    });

</script>


</html>