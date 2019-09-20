<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:35:21 +0800
 */
namespace fwkit\Wechat\Message;

class Link extends MessageBase
{
    public $title;

    public $description;

    public $url;

    protected function initialize()
    {
        $this->title = $this->get('title');
        $this->description = $this->get('description');
        $this->url = $this->get('url');
    }
}
