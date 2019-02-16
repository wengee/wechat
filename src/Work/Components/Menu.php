<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 11:02:24 +0800
 */
namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;
use fwkit\Wechat\Utils\Items\Button;

class Menu extends ComponentBase
{
    public function createButton($args, ?string $type = null): Button
    {
        if ($args instanceof Button) {
            return $args;
        }

        if (is_string($args)) {
            $args = ['name' => $args];
        }

        if (is_array($args)) {
            if ($type !== null) {
                $args['type'] = $type;
            }

            return new Button($args);
        }

        throw new \Exception('Params not valid');
    }

    public function create(int $agentId, array $buttons)
    {
        $buttons = $this->filterButtons($buttons);
        $res = $this->post('cgi-bin/menu/create', [
            'query' => ['agentid' => $agentId],
            'json' => ['button' => $buttons],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function fetch(int $agentId)
    {
        $res = $this->get('cgi-bin/menu/get', [
            'query' => ['agentid' => $agentId],
        ]);

        return $this->checkResponse($res, [
            'agentid' => 'agentId',
            'sub_button' => 'children',
            'appid' => 'appId',
            'pagepath' => 'pagePath',
            'media_id' => 'mediaId',
        ]);
    }

    public function clear(int $agentId)
    {
        $res = $this->get('cgi-bin/menu/delete', [
            'query' => ['agentid' => $agentId],
        ]);

        $this->checkResponse($res);
        return true;
    }

    protected function filterButtons(array $buttons): array
    {
        return array_map(function ($button) {
            if (is_array($button)) {
                return new Button($button);
            } elseif ($button instanceof Button) {
                return $button;
            }

            return null;
        }, $buttons);
    }
}
