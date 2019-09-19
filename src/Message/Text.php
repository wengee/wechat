<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-14 17:51:59 +0800
 */
namespace fwkit\Wechat\Message;

class Text extends MessageBase
{
    protected $properties = [
        'content',
        'menuId',
    ];

    public $content;

    public $menuId;

    protected function initialize(array $data)
    {
        $this->setData($data, [
            'bizmsgmenuid' => 'menuId',
        ]);
    }
}
