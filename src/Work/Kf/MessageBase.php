<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-09 17:46:04 +0800
 */

namespace fwkit\Wechat\Work\Kf;

abstract class MessageBase
{
    public $id;

    public $type;

    public $origin;

    public $kfId;

    public $userId;

    public $sendTime;

    public $data = [];

    protected static $types = [
        'event' => Event::class,
        'file'  => File::class,
        'image' => Image::class,
        'text'  => Text::class,
        'video' => Video::class,
        'voice' => Voice::class,
    ];

    public function __construct(array $msgData)
    {
        $this->id       = $msgData['msgId'] ?? null;
        $this->type     = $msgData['msgType'] ?? null;
        $this->origin   = $msgData['origin'] ?? null;
        $this->kfId     = $msgData['kfId'] ?? null;
        $this->userId   = $msgData['userId'] ?? null;
        $this->sendTime = $msgData['sendTime'] ?? null;

        if ($this->type) {
            $this->data = $msgData[$this->type] ?? [];
            $this->initialize($this->data);
        }
    }

    public static function factory(array $data)
    {
        $msgType = $data['msgType'] ?? null;
        if (!$msgType) {
            $className = Other::class;
        } else {
            $msgType   = \strtolower($msgType);
            $className = static::$types[$msgType] ?? Other::class;
        }

        return new $className($data);
    }

    protected function initialize(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}
