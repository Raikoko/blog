@extends('layouts.base')

@section('title')
    张家犬请滚进来
@endsection
@section('content')

    <body class="layui-layout-body">
    <div class="login-main">
        <header class="layui-elip">欢迎来到张家犬的小窝</header>
        <form class="layui-form">
            <div class="layui-input-inline">
                <video src="{{asset('video/zjm.mp4')}}" style="width: 300px;height: 300px;" controls autoplay></video>
            </div>
            <hr/>
        </form>
    </div>

    <script>
        layui.use(['element','layer'], function(){
            let element = layui.element;
            let $ = layui.$;
            let layer = layui.layer;
            let user_info = layui.data('user_info').data.user_info;

            //写入用户信息
            let html = '<img src="http://t.cn/RCzsdCq" class="layui-nav-img" >'+user_info.username;
            $('#user').append(html);

        });
    </script>
    </body>

@endsection
