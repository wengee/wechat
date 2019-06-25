<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-06-25 14:46:43 +0800
 */
namespace fwkit\Wechat\Minapp;

use fwkit\Wechat\ClientBase;

class Client extends ClientBase
{
    protected $componentList = [
        'analysis'  => Components\Analysis::class,
        'media'     => Components\Media::class,
        'message'   => Components\Message::class,
        'nearby'    => Components\Nearby::class,
        'oauth'     => Components\OAuth::class,
        'pay'       => Components\Pay::class,
        'plugin'    => Components\Plugin::class,
        'qrcode'    => Components\QrCode::class,
        'security'  => Components\Security::class,
        'template'  => Components\Template::class,
        'token'     => Components\Token::class,
    ];

    protected $baseUri = 'https://api.weixin.qq.com/';
}
