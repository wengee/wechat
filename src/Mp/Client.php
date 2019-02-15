<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 16:02:00 +0800
 */
namespace fwkit\Wechat\Mp;

use fwkit\Wechat\ClientBase;

class Client extends ClientBase
{
    protected $componentList = [
        'base'      => Components\Base::class,
        'comment'   => Components\Comment::class,
        'material'  => Components\Material::class,
        'media'     => Components\Media::class,
        'menu'      => Components\Menu::class,
        'message'   => Components\Message::class,
        'oauth'     => Components\OAuth::class,
        'qrcode'    => Components\QrCode::class,
        'tag'       => Components\Tag::class,
        'token'     => Components\Token::class,
        'user'      => Components\User::class,
    ];

    protected $baseUri = 'https://api.weixin.qq.com/';
}
