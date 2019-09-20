<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-09-20 14:25:49 +0800
 */
namespace fwkit\Wechat\Message\Event;

class View extends EventBase
{
    public $menuId;

    protected function initialize()
    {
        $this->menuId = $this->get('menuId');
    }
}
