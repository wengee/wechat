<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message;

class Link extends MessageBase
{
    public $title;

    public $description;

    public $url;

    protected function initialize(): void
    {
        $this->title = $this->get('title');
        $this->description = $this->get('description');
        $this->url = $this->get('url');
    }
}
