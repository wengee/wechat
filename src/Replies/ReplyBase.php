<?php

namespace fwkit\Wechat\Replies;

class ReplyBase
{
    protected $data;

    public function __construct(array $data)
    {
        if (!isset($data['time'])) {
            $data['time'] = time();
        }

        $this->data = $data;
    }

    public function render()
    {
        $search = $replace = [];
        foreach ($this->data as $key => $value) {
            $search[] = '{' . $key . '}';
            $replace[] = $value;
        }

        return str_replace($search, $replace, static::$template);
    }
}
