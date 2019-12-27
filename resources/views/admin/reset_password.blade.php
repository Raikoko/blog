

@extends('layouts.base')

@section('title')
    找回密码
@endsection

@section('content')

    <body>
    <div class="login-main">
        <header class="layui-elip" style="width: 82%">找回密码</header>

        <form class="layui-form">
            {{csrf_field()}}
            <div class="layui-input-inline">
                <div class="layui-inline" style="width: 85%">
                    <input type="password" id="pwd" name="password" required  lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-inline">
                    <i class="layui-icon" id="pri" style="color: green;font-weight: bolder;" hidden></i>
                </div>
                <div class="layui-inline">
                    <i class="layui-icon" id="pwr" style="color: red; font-weight: bolder;" hidden>ဆ</i>
                </div>
            </div>
            <div class="layui-input-inline">
                <div class="layui-inline" style="width: 85%">
                    <input type="password" id="rpwd" name="repassword" required  lay-verify="required" placeholder="请确认密码" autocomplete="off" class="layui-input">
                </div>
                <div class="layui-inline">
                    <i class="layui-icon" id="rpri" style="color: green;font-weight: bolder;" hidden></i>
                </div>
                <div class="layui-inline">
                    <i class="layui-icon" id="rpwr" style="color: red; font-weight: bolder;" hidden>ဆ</i>
                </div>
            </div>


            <div class="layui-input-inline login-btn" style="width: 85%">
                <button type="submit" lay-submit lay-filter="reset" class="layui-btn">重新设置</button>
            </div>
            <hr style="width: 85%" />
        </form>
    </div>
    </body>

    <script>
        layui.use(['layer','table'],function () {
            let table = layui.table;
            let layer = layui.layer;
            let form = layui.form;
            let $ = layui.$;
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

            //重新设置密码
            form.on('submit(reset)', function(data){
                //获取URL中的token
                let url = location.href;
                let strs = url.split("/");
                let token = strs[strs.length-1];

                $.ajax({
                    type:'POST',
                    url:'{{url('admin/reset')}}',
                    headers:{'Authorization':'Bearer '+token},
                    data:data.field,
                    success:function (res) {
                        layer.msg(res.msg);
                        if(res.code == 0){
                            setTimeout(function () {
                                location.href = "{{url('admin/login')}}";
                            },3000);
                        }
                    }
                });
                return false;
            });

        });

    </script>

@endsection
