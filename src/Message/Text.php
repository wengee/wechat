<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:35:36 +0800
 */
namespace fwkit\Wechat\Message;

class Text extends MessageBase
{
    public $content;

    public $menuId;

    protected function initialize()
    {
        $this->content = $this->get('content');
        $this->menuId = $this->get('bizMsgMenuId');
    }
}
