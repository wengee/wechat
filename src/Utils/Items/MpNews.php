<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-10-16 10:34:41 +0800
 */
namespace fwkit\Wechat\Utils\Items;

use fwkit\Wechat\Concerns\HasOptions;
use JsonSerializable;

class MpNews implements JsonSerializable
{
    use HasOptions;

    public $title;

    public $thumbMediaId;

    public $author;

    public $sourceUrl;

    public $content;

    public $description;

    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

    public function jsonSerialize()
    {
        return [
            'title'                 => $this->title,
            'thumb_media_id'        => $this->thumbMediaId,
            'author'                => $this->author,
            'content_source_url'    => $this->sourceUrl,
            'content'               => $this->content,
            'digest'                => $this->description,
        ];
    }
}
