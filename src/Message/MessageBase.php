<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-04-24 11:58:20 +0800
 */

namespace fwkit\Wechat\Message;

use fwkit\Wechat\Utils\Helper;
use SimpleXMLElement;

abstract class MessageBase
{
    public $id;

    public $type;

    public $accountId;

    public $openId;

    public $createTime;
    protected static $types = [
        'image'      => Image::class,
        'link'       => Link::class,
        'location'   => Location::class,
        'shortvideo' => ShortVideo::class,
        'text'       => Text::class,
        'video'      => Video::class,
        'voice'      => Voice::class,
        'file'       => File::class,
        'info'       => Info::class,
    ];

    protected static $events = [
        'click'                      => Event\Click::class,
        'location'                   => Event\Location::class,
        'location_select'            => Event\LocationSelect::class,
        'pic_photo_or_album'         => Event\PicPhotoOrAlbum::class,
        'pic_sysphoto'               => Event\PicSysPhoto::class,
        'pic_weixin'                 => Event\PicWeixin::class,
        'scan'                       => Event\Scan::class,
        'scancode_push'              => Event\ScanCodePush::class,
        'scancode_waitmsg'           => Event\ScanCodeWaitMsg::class,
        'subscribe'                  => Event\Subscribe::class,
        'unsubscribe'                => Event\Unsubscribe::class,
        'view'                       => Event\View::class,
        'view_miniprogram'           => Event\ViewMiniProgram::class,
        'subscribe_msg_popup_event'  => Event\SubscribeMsgPopup::class,
        'subscribe_msg_change_event' => Event\SubscribeMsgChange::class,
        'subscribe_msg_sent_event'   => Event\SubscribeMsgSent::class,
        'user_info_modified'         => Event\UserInfoModified::class,
        'user_authorization_revoke'  => Event\UserAuthorizationRevoke::class,
        'wxa_media_check'            => Event\WxaMediaCheck::class,
    ];

    protected static $replies = [
        'image' => Reply\Image::class,
        'music' => Reply\Music::class,
        'news'  => Reply\News::class,
        'text'  => Reply\Text::class,
        'video' => Reply\Video::class,
        'voice' => Reply\Voice::class,
        'raw'   => Reply\Raw::class,
    ];

    protected $attributes = [];

    protected $rawMsg;

    protected $isJson = false;

    protected $data;

    protected $cryptor;

    public function __construct(string $rawMsg, array $data, bool $isJson = false)
    {
        $this->rawMsg = $rawMsg;
        $this->isJson = $isJson;

        $this->setData($data);
        $this->initialize();
    }

    public function __get(string $property)
    {
        $method = 'get'.ucfirst($property);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return $this->attributes[$property] ?? null;
    }

    public function setCryptor($cryptor)
    {
        $this->cryptor = $cryptor;

        return $this;
    }

    public function get($key, $default = null)
    {
        $key = strtolower($key);

        return $this->data[$key] ?? $default;
    }

    /**
     * @deprecated deprecated since version 1.0
     * @see self::rawMsg()
     */
    public function rawXml(): ?string
    {
        return $this->rawMsg;
    }

    public function rawMsg(): ?string
    {
        return $this->rawMsg;
    }

    public function isJson(): bool
    {
        return $this->isJson;
    }

    public function reply(string $type = 'text')
    {
        $className = static::$replies[$type] ?? Reply\Unknown::class;
        $reply     = new $className($this->accountId, $this->openId);
        $reply->setCryptor($this->cryptor);

        return $reply;
    }

    public static function factory(string $message)
    {
        $isJson = false;

        /** @var bool|SimpleXMLElement $xml */
        $xml = simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (false === $xml) {
            $data = json_decode($message, true);
            if (is_array($data)) {
                $isJson = true;
                $data   = array_change_key_case($data, CASE_LOWER);
            } else {
                $data = [];
            }
        } else {
            $data = $xml ? Helper::parseXmlElement($xml) : [];
        }

        $msgType = $data['msgtype'] ?? (isset($data['infotype']) ? 'info' : null);
        if (empty($data) || !$msgType) {
            return null;
        }

        $msgType = \strtolower($msgType);
        if ('event' === $msgType) {
            $event     = strtolower($data['event']);
            $className = static::$events[$event] ?? Event\Unknown::class;
        } else {
            $className = static::$types[$msgType] ?? Unknown::class;
        }

        return new $className($message, $data, $isJson);
    }

    public function withAttribute($name, $value): self
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function withoutAttribute($name): self
    {
        unset($this->attributes[$name]);

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    public function isEvent(...$types): bool
    {
        return false;
    }

    protected function setData(array $data): void
    {
        $this->data = $data;

        $this->id         = $data['msgid'] ?? null;
        $this->type       = isset($data['msgtype']) ? strtolower($data['msgtype']) : null;
        $this->accountId  = $data['tousername'] ?? null;
        $this->openId     = $data['fromusername'] ?? null;
        $this->createTime = (int) $data['createtime'] ?? 0;
    }

    protected function initialize(): void
    {
    }
}
