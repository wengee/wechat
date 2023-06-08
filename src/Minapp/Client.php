<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2023-06-08 11:35:46 +0800
 */

namespace fwkit\Wechat\Minapp;

use fwkit\Wechat\ClientBase;

/**
 * @method Components\Analysis  getAnalysisComponent()
 * @method Components\Media     getMediaComponent()
 * @method Components\Message   getMessageComponent()
 * @method Components\Nearby    getNearbyComponent()
 * @method Components\OAuth     getOAuthComponent()
 * @method Components\Pay       getPayComponent()
 * @method Components\Plugin    getPluginComponent()
 * @method Components\QrCode    getQrCodeComponent()
 * @method Components\Redpacket getRedpacketComponent()
 * @method Components\Search    getSearchComponent()
 * @method Components\Security  getSecurityComponent()
 * @method Components\Soter     getSoterComponent()
 * @method Components\Subscribe getSubscribeComponent()
 * @method Components\Template  getTemplateComponent()
 * @method Components\Token     getTokenComponent()
 * @method Components\Url       getUrlComponent()
 */
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
