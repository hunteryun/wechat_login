#用法：
======================

1. Scopes为 snsapi_base（不需用户同意，只能获取到openid）
与 snsapi_userinfo (需用户同意,可获得详细用户信息) 时，你
先得有一个认证过的公众号，然后在开发->接口权限->网页授权->
点击后面的修改，修改为你的域名,如www.xxx.com

2. Scopes为 snsapi_login 时，你得去微信开放平台，创建一个网站应用

3. 在后台填写对应平台（开放平台或公众号平台）AppID 和 AppSecret

4. 如果是公众平台，则在你域名授权的网站路径router里加上wechat_oauth
中间件，如：

```
front.home:
  path: '/'
  defaults:
    _controller: '\Hunter\front\Controller\FrontController::home'
    _title: 'Home'
  requirements:
    _permission: 'wechat_oauth'

```
5. 如果是开放平台网页扫码登录，则自行在模板里调用/wechat/login链接
