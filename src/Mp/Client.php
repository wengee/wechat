<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2023-06-08 11:35:37 +0800
 */

namespace fwkit\Wechat\Mp;

use fwkit\Wechat\ClientBase;
use fwkit\Wechat\ThirdParty\ThirdClientInterface;

/**
 * @method Components\Base         getBaseComponent()
 * @method Components\Comment      getCommentComponent()
 * @method Components\JsApi        getJsApiComponent()
 * @method Components\Material     getMaterialComponent()
 * @method Components\Media        getMediaComponent()
 * @method Components\Menu         getMenuComponent()
 * @method Components\Message      getMessageComponent()
 * @method Components\Notification getNotificationComponent()
 * @method Components\OAuth        getOAuthComponent()
 * @method Components\Pay          getPayComponent()
 * @method Components\Publish      getPublishComponent()
 * @method Components\QrCode       getQrCodeComponent()
 * @method Components\Redpack      getRedpackComponent()
 * @method Components\Statis       getStatisComponent()
 * @method Components\Tag          getTagComponent()
 * @method Components\Template     getTemplateComponent()
 * @method Components\Token        getTokenComponent()
 * @method Components\User         getUserComponent()
 */
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
        'publish'      => Components\Publish::class,
        'qrcode'       => Components\QrCode::class,
        'redpack'      => Components\Redpack::class,
        'statis'       => Components\Statis::class,
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
