<?php
return [
    'pay' => [
        // APPID
        'app_id' => '2016102100731104',
        // 支付宝 支付成功后 主动通知商户服务器地址  注意 是post请求
//        'notify_url' => 'http://raiko.free.idcfengye.com/ali/pay_notify',
        'notify_url' => 'http://raiko.ngrok2.xiaomiqiu.cn/ali/pay_notify',

        // 支付宝 支付成功后 回调页面 get
//        'return_url' => 'http://raiko.free.idcfengye.com/ali/pay_success',
        'return_url' => 'http://raiko.ngrok2.xiaomiqiu.cn/ali/pay_success',

        // 公钥（注意是支付宝的公钥，不是商家应用公钥）
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi1qbJKJWIsKDeHP6WCASXa56UF069RdeIZa7xJeygJnCHAdwpGIaHFabqIbjFz87pdprz1GFGvcvE8txT5AKm9Q0/zEBAnQL3mahIkdRg/00WeqgBta72yqGvUH4t2/eLX/CANBZDVTuRD07ZFXMI/+n31Kwu7FZuwPlYod4KewTvHOABVKDfpdpsTVcBMXtD+KOnJYY3UF463B/NSmeIEwFk1rnLRbmv+SYu5EhB5ZuwRp0NRrhxiS16nT1yVbf0KUGzzFcOWOjJEhcZCuvetrzS/IBoxDcme2jPbA/hN/OuiBca1EBMKhJJPySz6Ig1iiDAQQDZ9ntJRUsiuFrjQIDAQAB',
        // 加密方式： **RSA2** 私钥 商家应用私钥
        'private_key' => 'MIIEpAIBAAKCAQEAraJoMz9CIuZf/uTXmWJ5gWgXuhdnvlncGAu5oYB5jnkp6QN2qIDDMFv2Cp31AEwC6hYctdBHzxWL14PfFRi6KfZSZw4Uz8J8s5rE6tDKtcRLU8k/Cmzf/5hrvKO2Bh3k/01j8yDbbDHcQs7Q4cr9KDykR2lllfRdrJcKRyyBxrIwdpcqRY9zEFNS2Je05yBizhgiCJx3NO90EMqyYN9lD6LF52j/NeL6MqFrxty5aI2dNxveUvlSbzjegSzgZ0zckh+HKWyLrLEBW4GmKte0kxA4D8iAT2Y8cp588sGlmlRe2AzGQlc5Hnxse/FLvOoOwjF3E4b4MOtvdkHs8WKnVQIDAQABAoIBAEziG9eNTCCd96Wq+NhfJUQqrk7lLDe6HF29W13qwGhYuDkIIpNsKIGSRrjexmxyO9whHGdZKWLFvJ0aND5oK46HFmexcX+pM4RnpIgBGbEwkNYxxys7mFfZyuLFbsT4mbx+LSKrytz2mvwuDYndUwltCKTiQ2wJvyy72H45c+Fhqfmunp7uHR8vh88azhjGNQfVsCezQwKgldul6hYuN/uS5Uxwpt0ya96/vvtbvO9/aqkGhuIsdcHc/yPR6vEHKzrh3aqHB4YCJP0FpQupnsm5psrV+YZCvXLTRQoWbICO5FIcGmiMmNjUc4fDvcOfcf0DqDnaV9hKe8F2KxEMvsECgYEA+ZaTh7E2j0D6tl94qPlDQp08wr61dDPkg0oBZnR7G6LtQjMKlRaKzcv8gnncaCAHEdtGJDN2QKZhfDZ/9eN97crc9Ca0WOQN1gBFCoIMMca1W3yA/aOCRl+x1xKTk/GBQrykyTEzWID/Ry+gSElu3zn8SHD/SQet5CkUBtnnWhECgYEAshhRi+3WhVEiuAbfm3It6uXfwbpQo0VTu9o5Bu+gbHL0uU2K0iID0Zi6nYeEzQHOji2Ey+2an9JTs2jqhVaK7FNQb/ME9xzacCE1zAkR/kGjEERMvRQb5OAPc4ACZVWfakJpmS5CyRetU8T80EFD//g67EyZwQLGQVlIkj9SlQUCgYEA1UpuzTkOTSHUn2G7NXu+lQDWWf6sBqAKGoB17d6BDj8PztDbrEjVWcWHBuGpD3q/T+05ZdpsphcJnCLe+3zrsvj4FdW1rXlGsakNV0uHrEoJ1iLyAM9ol0dULV38rCNouWWI1T6siGfEwErdCKlojG97P1UBdKGRYzgPm0NASGECgYBNl4zHwzqIYP4YYV5AsnFQ+xAOrmb9i3Hc0UmxPOuO9FKs/RyzSoCRa9I6WdXBH71ncmt+EaohYZh3/QjYSQlDx1SvQZjzNM3ytnVizLzbIquxpaAtbLpucDQrJSVmEvTebcrmIyKTzE4GxjdDYHwI5JR/aSw/zD6mMAkPNql7JQKBgQC6TjnnBzSejohVGhrtbz9XGApF0fsuidC+plsBxnIvEJAkGCGPTvWbGAWlR3qky9UJPhnqoEOS2SshcTbbo9zCNroFqNxC/W6DNpBAcHIeZfSGHuFdssipn/qPkJSqwVitoJjw0qxvfVZCX7wevFBsSq/nIapdsrdkdChe5+2Ubw==',
        'log' => [ // optional
            'file' => '../storage/logs/alipay.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ],
        'http' => [
            'timeout' => 5.0,
            'connect_timeout' => 5.0,
            // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
        ],
        'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
    ]

];
