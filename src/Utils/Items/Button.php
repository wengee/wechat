<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Utils\Items;

use fwkit\Wechat\Traits\HasOptions;
use JsonSerializable;

class Button implements JsonSerializable
{
    use HasOptions;

    public const CLICK = 'click';

    public const VIEW = 'view';

    public const SCANCODE_PUSH = 'scancode_push';

    public const SCANCODE_WAITMSG = 'scancode_waitmsg';

    public const PIC_SYSPHOTO = 'pic_sysphoto';

    public const PIC_PHOTO_OR_ALBUM = 'pic_photo_or_album';

    public const PIC_WEIXIN = 'pic_weixin';

    public const LOCATION_SELECT = 'location_select';

    public const MEDIA_ID = 'media_id';

    public const VIEW_LIMITED = 'view_limited';

    public const MINI_PROGRAM = 'miniprogram';

    protected $children = [];

    public $type;

    public $name;

    public $key;

    public $url;

    public $appId;

    public $pagePath;

    public $mediaId;

    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

    public function addChild($button)
    {
        if (is_array($button)) {
            $button = new self($button);
        }

        if ($button instanceof self) {
            $this->children[] = $button;
            return true;
        }

        return false;
    }

    public function setChildren(array $buttons): void
    {
        $this->children = [];
        foreach ($buttons as $button) {
            if (is_array($button)) {
                $button = new self($button);
            }

            if ($button instanceof self) {
                $this->children[] = $button;
            }
        }
    }

    public function toArray(): array
    {
        $data = ['name' => $this->name];
        if (empty($this->children)) {
            $data['type'] = $this->type;

            switch ($this->type) {
                case self::VIEW:
                    $data['url'] = $this->url;
                    break;

                case self::CLICK:
                case self::SCANCODE_PUSH:
                case self::SCANCODE_WAITMSG:
                case self::PIC_SYSPHOTO:
                case self::PIC_PHOTO_OR_ALBUM:
                case self::PIC_WEIXIN:
                case self::LOCATION_SELECT:
                    $data['key'] = $this->key;
                    break;

                case self::MINI_PROGRAM:
                    $data['appid'] = $this->appId;
                    $data['pagepath'] = $this->pagePath;
                    $data['url'] = $this->url;
                    break;

                case self::MEDIA_ID:
                case self::VIEW_LIMITED:
                    $data['media_id'] = $this->mediaId;
                    break;

                default:
                    $data['type'] = self::CLICK;
                    $data['key'] = $this->key ?: $this->name;
            }
        } else {
            $data['sub_button'] = array_map(function ($button) {
                return $button->toArray();
            }, $this->children);
        }

        return $data;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
