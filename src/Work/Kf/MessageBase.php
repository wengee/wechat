<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2022-06-10 17:43:02 +0800
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

    protected $raw;

    protected static $types = [
        'event' => Event::class,
        'file'  => File::class,
        'image' => Image::class,
        'text'  => Text::class,
        'video' => Video::class,
        'voice' => Voice::class,
    ];

    public function __construct(array $rawData)
    {
        $this->raw      = $rawData;
        $this->id       = $rawData['msgId'] ?? null;
        $this->type     = $rawData['msgType'] ?? null;
        $this->origin   = $rawData['origin'] ?? null;
        $this->kfId     = $rawData['kfId'] ?? null;
        $this->userId   = $rawData['userId'] ?? null;
        $this->sendTime = $rawData['sendTime'] ?? null;

        if ($this->type) {
            $this->data = $rawData[$this->type] ?? [];
            $this->initialize($this->data);
        }
    }

    public static function factory(array $rawData)
    {
        $msgType = $rawData['msgType'] ?? null;
        if (!$msgType) {
            $className = Other::class;
        } else {
            $msgType   = \strtolower($msgType);
            $className = static::$types[$msgType] ?? Other::class;
        }

        return new $className($rawData);
    }

    public function raw(): array
    {
        return $this->raw ?: [];
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
