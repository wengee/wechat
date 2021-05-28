<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-05-28 10:51:35 +0800
 */

namespace fwkit\Wechat\Message;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;

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

    protected $rawXml;

    protected $data;

    protected $cryptor;

    public function __construct(string $rawXml, array $data)
    {
        $this->rawXml = $rawXml;

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

        return Arr::get($this->data, $key, $default);
    }

    public function rawXml()
    {
        return $this->rawXml;
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
        $data = json_decode(json_encode(simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        $data = array_change_key_case_recursive($data);

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

        return new $className($message, $data);
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
