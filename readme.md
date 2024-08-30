# V2Board接入VouCash
V2Board VouCash支付插件 支持USDT，关于 [VouCash](https://github.com/voucash/voucash)

### 设置
```
1. 下载 SDK
    下载app/Payments/voucash.php，并上传到面板app/Payments目录中
2. 面板管理后台 > 系统配置 > 站点 
    添加你的网站域名，不然在后面添加支付会提示失败
3. 添加 VouCash 支付方式
    面板管理后台 > 支付配置 > + 添加支付方式
    显示名称	VouCash代金券
    接口文件	VouCash
3. 启用该支付方式
```


### 兑现
1. 用户支付后，面板管理后台 > 订单管理 > 点击订单号 > 订单详情

![v2board支付成功](https://raw.githubusercontent.com/voucash/learncoins/master/images/v2board.png)

3. 复制回调单号到 [VouCash提现](https://voucash.com/cn/redeem){:target="_blank"}

## 有问题和合作可以小飞机联系我们
 - telegram：@voucash
