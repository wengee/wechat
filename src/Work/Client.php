<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 16:58:46 +0800
 */
namespace fwkit\Wechat\Work;

use fwkit\Wechat\ClientBase;

class Client extends ClientBase
{
    protected $componentList = [
        'agent'         => Components\Agent::class,
        'base'          => Components\Base::class,
        'batch'         => Components\Batch::class,
        'department'    => Components\Department::class,
        'jsapi'         => Components\JsApi::class,
        'media'         => Components\Media::class,
        'menu'          => Components\Menu::class,
        'message'       => Components\Message::class,
        'oauth'         => Components\OAuth::class,
        'redpack'       => Components\Redpack::class,
        'tag'           => Components\Tag::class,
        'token'         => Components\Token::class,
        'user'          => Components\User::class,
    ];

    protected $baseUri = 'https://qyapi.weixin.qq.com/';
}
