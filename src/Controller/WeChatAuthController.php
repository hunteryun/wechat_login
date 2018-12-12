<?php

namespace Hunter\wechat_login\Controller;

use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\JsonResponse;
use EasyWeChat\Factory;

/**
 * Class WeChatAuthController.
 *
 * @package Hunter\wechat_login\Controller
 */
class WeChatAuthController {
  /**
   * wechat_auth_callback.
   *
   * @return string
   *   Return wechat_auth_callback string.
   */
  public function wechat_auth_callback(ServerRequest $request) {
    $config = [
      'app_id' => variable_set('wechat_login_appid'),
      'secret' => variable_set('wechat_login_appsecret'),
      'oauth' => [
          'scopes'   => [variable_set('wechat_login_client_scope')],
          'callback' => '/wechat/login/callback',
      ],
    ];

    $app = Factory::officialAccount($config);
    $oauth = $app->oauth;

    // 获取 OAuth 授权结果用户信息
    $user = $oauth->user();
    session()->set('curuser', $user->toArray());
    return redirect('/');
  }

}
