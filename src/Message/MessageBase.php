<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-06-26 23:16:03 +0800
 */
namespace fwkit\Wechat\Message;

use Illuminate\Support\Collection;

abstract class MessageBase
{
    protected static $types = [
        'image'         => Image::class,
        'link'          => Link::class,
        'location'      => Location::class,
        'shortvideo'    => ShortVideo::class,
        'text'          => Text::class,
        'video'         => Video::class,
        'voice'         => Voice::class,
        'file'          => File::class,
    ];

    protected static $events = [
        'click'         => Event\Click::class,
        'location'      => Event\Location::class,
        'scan'          => Event\Scan::class,
        'subscribe'     => Event\Subscribe::class,
        'unsubscribe'   => Event\Unsubscribe::class,
        'view'          => Event\View::class,
    ];

    protected static $replies = [
        'image'         => Reply\Image::class,
        'music'         => Reply\Music::class,
        'news'          => Reply\News::class,
        'text'          => Reply\Text::class,
        'video'         => Reply\Video::class,
        'voice'         => Reply\Voice::class,
        'raw'           => Reply\Raw::class,
    ];

    protected $attributes;

    protected $rawXml;

    protected $data;

    protected $cryptor;

    public $id;

    public $type;

    public $accountId;

    public $openId;

    public $createTime;

    public function __construct(string $rawXml, array $data)
    {
        $this->attributes = new Collection;
        $this->rawXml = $rawXml;
        $this->data = $data;

        $this->initialize($data);
    }

    public function setCryptor($cryptor)
    {
        $this->cryptor = $cryptor;
        return $this;
    }

    public function get($key, $default = null)
    {
        $key = strtolower($key);
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    public function rawXml()
    {
        return $this->rawXml;
    }

    public function reply(string $type = 'text')
    {
        $className = static::$replies[$type] ?? Reply\Unknown::class;
        $reply = new $className($this->accountId, $this->openId);
        $reply->setCryptor($this->cryptor);
        return $reply;
    }

    public static function factory(string $message)
    {
        $data = (array) @simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = array_change_key_case($data, CASE_LOWER);

        if (empty($data) || !isset($data['msgtype'])) {
            return null;
        }

        $msgType = strtolower($data['msgtype']);
        if ($msgType === 'event') {
            $event = strtolower($data['event']);
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
        return $this->attributes->all();
    }

    public function getAttribute($name, $default = null)
    {
        return $this->attributes->get($name, $default);
    }

    public function isEvent(...$types): bool
    {
        return false;
    }

    protected function setData(array $data, array $map = [])
    {
        $this->id = $data['msgid'] ?? null;
        $this->type = isset($data['msgtype']) ? strtolower($data['msgtype']) : null;
        $this->accountId = $data['tousername'] ?? null;
        $this->openId = $data['fromusername'] ?? null;
        $this->createTime = (int) $data['createtime'] ?? 0;

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
                continue;
            }

            if (isset($map[$key])) {
                $nKey = $map[$key];

                if (property_exists($this, $nKey)) {
                    $this->{$nKey} = $value;
                }
            }
        }
    }

    protected function initialize(array $data)
    {
        $this->setData($data);
    }
}
