<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-19 10:08:51 +0800
 */
namespace fwkit\Wechat\Message;

class Link extends MessageBase
{
    protected $properties = [
        'title',
        'description',
        'url',
    ];

    public $title;

    public $description;

    public $url;
}
