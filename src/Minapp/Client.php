<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-11-12 09:53:08 +0800
 */

namespace fwkit\Wechat\Minapp;

use fwkit\Wechat\ClientBase;

class Client extends ClientBase
{
    protected $type = self::TYPE_MINAPP;

    protected $componentList = [
        'analysis'  => Components\Analysis::class,
        'media'     => Components\Media::class,
        'message'   => Components\Message::class,
        'nearby'    => Components\Nearby::class,
        'oauth'     => Components\OAuth::class,
        'pay'       => Components\Pay::class,
        'plugin'    => Components\Plugin::class,
        'qrcode'    => Components\QrCode::class,
        'redpacket' => Components\Redpacket::class,
        'search'    => Components\Search::class,
        'security'  => Components\Security::class,
        'soter'     => Components\Soter::class,
        'subscribe' => Components\Subscribe::class,
        'template'  => Components\Template::class,
        'token'     => Components\Token::class,
        'url'       => Components\Url::class,
    ];

    protected $baseUri = 'https://api.weixin.qq.com/';
}
