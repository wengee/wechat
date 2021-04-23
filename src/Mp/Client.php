<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-04-23 10:05:01 +0800
 */

namespace fwkit\Wechat\Mp;

use fwkit\Wechat\ClientBase;
use fwkit\Wechat\ThirdParty\ThirdClientInterface;

class Client extends ClientBase
{
    protected $type = self::TYPE_MP;

    protected $componentList = [
        'base'         => Components\Base::class,
        'comment'      => Components\Comment::class,
        'jsapi'        => Components\JsApi::class,
        'material'     => Components\Material::class,
        'media'        => Components\Media::class,
        'menu'         => Components\Menu::class,
        'message'      => Components\Message::class,
        'notification' => Components\Notification::class,
        'oauth'        => Components\OAuth::class,
        'pay'          => Components\Pay::class,
        'qrcode'       => Components\QrCode::class,
        'redpack'      => Components\Redpack::class,
        'tag'          => Components\Tag::class,
        'template'     => Components\Template::class,
        'token'        => Components\Token::class,
        'user'         => Components\User::class,
    ];

    protected $baseUri = 'https://api.weixin.qq.com/';

    protected $thirdClient;

    public function setThirdClient(ThirdClientInterface $thirdClient)
    {
        $this->thirdClient = $thirdClient;

        return $this;
    }

    public function getThirdClient(): ?ThirdClientInterface
    {
        return $this->thirdClient ?: null;
    }
}
