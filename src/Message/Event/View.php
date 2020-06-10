<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Message\Event;

class View extends EventBase
{
    public $menuId;

    protected function initialize(): void
    {
        $this->menuId = $this->get('menuId');
    }
}
