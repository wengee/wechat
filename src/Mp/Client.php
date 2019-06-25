<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-06-25 17:52:30 +0800
 */
namespace fwkit\Wechat\Mp;

use fwkit\Wechat\ClientBase;

class Client extends ClientBase
{
    protected $componentList = [
        'base'      => Components\Base::class,
        'comment'   => Components\Comment::class,
        'jsapi'     => Components\JsApi::class,
        'material'  => Components\Material::class,
        'media'     => Components\Media::class,
        'menu'      => Components\Menu::class,
        'message'   => Components\Message::class,
        'oauth'     => Components\OAuth::class,
        'pay'       => Components\Pay::class,
        'qrcode'    => Components\QrCode::class,
        'redpack'   => Components\Redpack::class,
        'tag'       => Components\Tag::class,
        'template'  => Components\Template::class,
        'token'     => Components\Token::class,
        'user'      => Components\User::class,
    ];

    protected $baseUri = 'https://api.weixin.qq.com/';
}
