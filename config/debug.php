<?php

const login_check_normal = 0;     //普通方式
const login_check_auth = 1;       //框架auth


return [

    //登录方式
    'login_type'=> login_check_auth,

    //测试模式是否开启 true开启
    'debug'=>true,

    //短信验证码有效时间
    'sms_valid_time' => 60,


];
