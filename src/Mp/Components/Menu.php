<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-21 15:00:12 +0800
 */
namespace fwkit\Wechat\Mp\Components;

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

    public function create(array $buttons)
    {
        $buttons = $this->filterButtons($buttons);
        $res = $this->post('cgi-bin/menu/create', [
            'json' => [
                'button' => $buttons,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function fetch()
    {
        $res = $this->get('cgi-bin/menu/get');

        return $this->checkResponse($res, [
            'sub_button' => 'children',
            'menuid' => 'menuId',
            'conditionalmenu' => 'conditionalMenu',
            'matchrule' => 'matchRule',
            'tag_id' => 'tagId',
            'group_id' => 'groupId',
            'client_platform_type' => 'clientPlatformType',
            'appid' => 'appId',
            'pagepath' => 'pagePath',
            'media_id' => 'mediaId',
        ]);
    }

    public function clear()
    {
        $res = $this->get('cgi-bin/menu/delete');
        $this->checkResponse($res);
        return true;
    }

    public function createConditional(array $buttons, array $matchRule)
    {
        $buttons = $this->filterButtons($buttons);
        $matchRule = $this->transformKeys($matchRule, [
            'tagId' => 'tag_id',
            'platform' => 'client_platform_type',
            'clientPlatformType' => 'client_platform_type',
        ]);
        $ret = $this->post('cgi-bin/menu/addconditional', [
            'json' => [
                'button' => $buttons,
                'matchrule' => $matchRule,
            ],
        ]);

        $res = $this->checkResponse($res, ['menuid' => 'menuId']);
        return $res->menuId;
    }

    public function delConditional($menuId)
    {
        $res = $this->post('cgi-bin/menu/delconditional', [
            'json' => [
                'menuid' => $menuId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function tryMatch($userId)
    {
        $res = $this->post('cgi-bin/menu/trymatch', [
            'json' => [
                'user_id' => $userId,
            ],
        ]);

        return $this->checkResponse($res, [
            'sub_button' => 'children',
        ]);
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
