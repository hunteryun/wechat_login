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
      'app_id' => variable_get('wechat_login_appid'),
      'secret' => variable_get('wechat_login_appsecret'),
      'oauth' => [
          'scopes'   => [variable_get('wechat_login_client_scope')],
          'callback' => '/wechat/login/callback',
      ],
    ];

    $app = Factory::officialAccount($config);
    $oauth = $app->oauth;

    // 获取 OAuth 授权结果用户信息
    $user = $oauth->user();
    $this->user_createorlogin($user->toArray());
    return redirect('/');
  }

  /**
   * Social create user after login.
   */
  public function user_createorlogin($user) {
    $existed_user = db_select('user', 'u')
              ->fields('u')
              ->condition('uuid', $user['id'])
              ->execute()
              ->fetchObject();

    if($existed_user){
      db_update('user')
        ->fields(array(
          'accessed' => time(),
        ))
        ->condition('uid', $existed_user->uid)
        ->execute();
      session()->set('curuser', $existed_user);
    }else{
      $uid = db_insert('user')
        ->fields(array(
          'uuid' => $user['id'],
          'username' => $user['name'],
          'password' => helper()->random(6),
          'nickname' => $user['nickname'],
          'email' => $user['email'],
          'provider' => 'wechat_login',
          'avatar' => $user['avatar'] ? $user['avatar'] : '/theme/hunter/assets/avatar/'.rand(0,38).'.jpg',
          'status' => 1,
          'created' => time(),
          'updated' => time(),
          'accessed' => time()
        ))
        ->execute();

      $new_user = db_select('user', 'u')
                ->fields('u')
                ->condition('uid', $uid)
                ->execute()
                ->fetchObject();

      session()->set('curuser', $new_user);
    }
  }

}
