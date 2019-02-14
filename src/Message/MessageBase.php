<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 17:50:33 +0800
 */
namespace fwkit\Wechat\Message;

use fwkit\Wechat\Message\Event\Unknown as UnknownEvent;
use fwkit\Wechat\Message\Unknown as UnknownMessage;

abstract class MessageBase
{
    protected static $types = [
        'image'   => Image::class,
        'link'   => Link::class,
        'location'   => Location::class,
        'shortvideo'    => ShortVideo::class,
        'text'    => Text::class,
        'video'    => Video::class,
        'voice'    => Voice::class,
    ];

    protected static $events = [
        'click' => Event\Click::class,
        'location' => Event\Location::class,
        'scan' => Event\Scan::class,
        'subscribe' => Event\Subscribe::class,
        'unsubscribe' => Event\Unsubscribe::class,
        'view' => Event\View::class,
    ];

    protected $rawXml;

    protected $data;

    public $id;

    public $type;

    public $accountId;

    public $openId;

    public $createTime;

    public function __construct(string $rawXml, array $data)
    {
        $this->rawXml = $rawXml;
        $this->data = $data;

        $this->initialize($data);
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

    public static function factory(string $message)
    {
        $data = (array) simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
        $data = array_change_key_case($data, CASE_LOWER);

        $msgType = strtolower($data['msgtype']);
        if ($msgType === 'event') {
            $event = strtolower($data['event']);
            $className = static::$events[$event] ?? UnknownEvent::class;
        } else {
            $className = static::$types[$msgType] ?? UnknownMessage::class;
        }

        return new $className($message, $data);
    }

    protected function setAttributes(array $data, array $map = [])
    {
        foreach ($data as $key => $value) {
            if ($key === 'tousername') {
                $this->accountId = $value;
            } elseif ($key === 'fromusername') {
                $this->openId = $value;
            } elseif ($key === 'createtime') {
                $this->createTime = $value;
            } elseif ($key === 'msgid') {
                $this->id = $value;
            } elseif ($key === 'msgtype') {
                $this->type = strtolower($value);
            } else {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                    continue;
                }

                if (isset($map[$key])) {
                    $nKey = $map[$key];

                    if (property_exists($this, $nKey)) {
                        $this->{$nKey} = $value;
                        continue;
                    }
                }
            }
        }
    }

    protected function initialize(array $data)
    {
        $this->setAttributes($data);
    }
}
