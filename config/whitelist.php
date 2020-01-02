<?php

//白名单,不需要token的路由
return [

    'url'=>[
        'admin/login',
        'admin/do_login',
        'admin/register_index',
        'admin/register_check',
        'admin/register',
        'get_captcha',
        'admin/index',
        'send_msg',
        'common/oauth',
        'common/callback',
        'common/test',
        'common/index',

        'ali/pay_index',     //支付首页
        'ali/pay_success',  //支付成功后的回调
        'ali/pay_notify',  //支付成功后的通知

        'ali/aliPayScan',   //扫码支付
        'ali/aliPay',       //网页支付

    ],
    'ip'=>[

    ],
];
