

@extends('layouts.base')

@section('title')
    支付首页
@endsection
@section('content')
    <body>
    <div class="login-main">
        <header class="layui-elip">支付方式</header>
        <form class="layui-form">
            {{csrf_field()}}
            <div class="layui-input-inline">
                <input type="text" id="id" name="id" required lay-verify="required" placeholder="商品ID" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <input type="text" name="amount" required lay-verify="required" placeholder="金额" autocomplete="off" class="layui-input">
            </div>
            <hr/>

            <div class="layui-input-inline">
                <button type="button" class="layui-btn type layui-btn-normal" data-type="web">网页支付</button>
            </div>

            <div class="layui-input-inline">
                <button type="button" class="layui-btn type layui-btn-normal" data-type="scan">扫码支付</button>
            </div>

            <div class="layui-input-inline">
                <button type="button" class="layui-btn type layui-btn-primary" data-type="app">APP支付</button>
            </div>

        </form>
        <div id="web-box">

        </div>
    </div>
    </body>
    <script>
        layui.use(['layer','form'],function () {
            let layer = layui.layer;
            let form = layui.form;
            let $ = layui.$;

            //支付方式
            let active = {
                web : function () {
                    let id = $('#id').val();
                    $.post('/ali/aliPay',{id:id},function (res) {
                        $('#web-box').append(res);
                    });
                },
                scan : function () {
                    let id = $('#id').val();
                    $.post('/ali/aliPayScan',{id:id},function (res) {
                        layer.open({
                            title:'支付宝扫一扫',
                            content:res
                        })
                    });
                },
                app : function () {
                    let id = $('#id').val();
                    $.post('/ali/aliPayApp',{id:id},function (res) {
                        location.href = res;
                    });
                },
            };

            $('.layui-btn.type').on('click',function () {
                let type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            })

        });



    </script>
@endsection



