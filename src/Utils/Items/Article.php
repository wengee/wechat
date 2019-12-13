<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-15 17:38:53 +0800
 */
namespace fwkit\Wechat\Utils\Items;

use fwkit\Wechat\Traits\HasOptions;
use JsonSerializable;

class Article implements JsonSerializable
{
    use HasOptions;

    public $title;

    public $thumbMediaId;

    public $author;

    public $digest;

    public $showCoverPic = false;

    public $content;

    public $contentSourceUrl = '';

    public $openComment = false;

    public $onlyFansComment = false;

    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

    public function showCoverPic(bool $show = true)
    {
        $this->showCoverPic = $show;
        return $this;
    }

    public function setSourceUrl(string $url)
    {
        $this->contentSourceUrl = $url;
        return $this;
    }

    public function openComment(bool $open = true, bool $onlyFansComment = false)
    {
        $this->openComment = $open;
        $this->onlyFansComment = $onlyFansComment;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'title'                     => $this->title,
            'thumb_media_id'            => $this->thumbMediaId,
            'author'                    => $this->author,
            'digest'                    => $this->digest,
            'show_cover_pic'            => $this->showCoverPic ? 1 : 0,
            'content'                   => $this->content,
            'content_source_url'        => $this->contentSourceUrl,
            'need_open_comment'         => $this->openComment ? 1 : 0,
            'only_fans_can_comment'     => $this->onlyFansComment ? 1 : 0,
        ];
    }
}
