<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message;

class Text extends MessageBase
{
    public $content;

    public $menuId;

    protected function initialize(): void
    {
        $this->content = $this->get('content');
        $this->menuId = $this->get('bizMsgMenuId');
    }
}
