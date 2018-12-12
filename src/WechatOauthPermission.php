<?php

namespace Hunter\wechat_login;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use EasyWeChat\Factory;

/**
 * Provides wechat_login module permission auth.
 */
class WechatOauthPermission {

  /**
   * Returns bool value of wechat login auth permission.
   *
   * @return bool
   */
  public function handle(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
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

    if (!$session = session()->get('curuser')) {
      return $oauth->redirect()->send();
    }else{
      return $next($request, $response);
    }
  }

}
