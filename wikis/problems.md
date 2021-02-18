## 导航

- [PHP终端环境对应不上](#PHP终端环境对应不上)
- [后台管理密码忘记了](#后台管理密码忘记了)
- [邮件服务](#邮件服务)
- [修改后台登录地址](#修改后台登录地址)
- [易支付配置](#易支付配置)
- [后台主题配置](#后台主题配置)
- [开启极验行为验证](#开启极验行为验证)
- [HTTPS-ERROR](#HTTPS-ERROR)
- [更换模板](#更换模板)
- [各支付对应配置](#各支付对应配置)

## PHP终端环境对应不上
服务器终端下执行以下命令将宝塔php版本设置为系统php-cli版本 
```
ln -sf /www/server/php/73/bin/php /usr/bin/php
```
根据自己宝塔安装的php版本执行，不要照抄，这里是/php/73，你如果是php7.2的话就是/php/72

## 后台管理密码忘记了
服务器终端下`cd 网站根目录`, 执行以下命令重置管理员密码：    
```
php artisan admin:reset-password
```

## 邮件服务
编辑根目录下`.env`配置，配置邮件服务     
```
# 邮件服务配置
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=server@emails.dujiaoka.com
MAIL_FROM_NAME=独角发卡
MAIL_ENCRYPTION=tls
```
如果不能发送邮件，就请尝试更换一下端口：`587` `465` `22`,挨个试试！      
MAIL_ENCRYPTION设置为ssl或者tls

记得改了`.env邮件配置`要去重启一下`Supervisor`的进程服务，否则不会生效！   
![重启进程](https://i.loli.net/2020/04/08/jGDBz6L12rHguni.png)  


## 修改后台登录地址
编辑项目根目录下`.env`里面的 `ADMIN_ROUTE_PREFIX`即可

## 易支付配置
~~市面上98%易支付都是彩虹的程序，独角数卡已经集成通用支付方式，但是由于请求支付地址不一样   
需要大家手动去改一下你使用的易支付的支付请求地址：   
网站根目录下`app\Http\Controllers\Pay\YipayController.php`第`11`行代码   
这里注意啊！！！很多人直接填写得易支付得url。其实一般都需要在url后面加`/submit.php`       
例如完整url就是`http://xxx.com/submit.php`       
具体可以参考每家易支付得支付文档【发起支付请求那个地方】~~     
下面是例子代码：    
```
 // 这里自己配置请求网关
    const PAY_URI = 'http://xxx.com/submit.php';
```
注意：     
```
1.8.2版本起，无需再修改代码文件方式，直接将易支付请求地址填入`商户key`即可
```


## 后台主题配置
编辑项目根目录下`.env`里面的 `ADMIN_SKIN`即可    


## 开启极验行为验证
打开`.env`编辑，新增或编辑以下配置：   
```
SH_GEETEST=true // true为开启极验，false为关闭.
GEETEST_ID=xxxxxxxxx // 极验配置id.
GEETEST_KEY=xxxxxxxxx // 极验配置key
```

## HTTPS-ERROR
强制开启https访问后，后台会报错  
`
The GET method is not supported for this route. Supported methods: POST
`   
解决方法是将.env文件配置里面得`ADMIN_HTTPS`设置为`ADMIN_HTTPS=true`

## 更换模板
后台-系统设置-模板。
目前独角数卡提供以下模板：   
```
layui   官方模板
luna    由github @Julyssn用户贡献
hyper   有github @bimoe用户贡献
```
## 各支付对应配置

| 支付选项 | 商户id | 商户key | 商户密钥 | 备注 |
| :-----| :----- | :----- | :----- | :----- |
| 支付宝官方(当面付、PC、wap)	 | 支付宝开放平台应用appid	 | 支付宝公钥 | 商户私钥 |  |
| payjs | payjs商户号(mchid)	 | 空 | payjs密钥 |  |
| 码支付 | 平台商户号	| 空 | 密钥 |  |
| 微信官方 | 公众号或小程序appid | 商户号 | 商户api密钥 |  |
| 麻瓜宝 | 商户密钥 | 空 | 任意字符串 |  |
| paysapi | 商户号 | 空 | 密钥 |  |
| 易支付 | 易支付 | 易支付请求网址 | 密钥 | ~~需要自行修改app/Http/Controllers/Pay/YipayController.php中的支付网关~~ |
| V免签 | V免签通讯密钥 | 空 | V免签地址 |  |
