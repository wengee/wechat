<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Menu extends ComponentBase
{
    public function create(array $buttons, array $conditions = [])
    {
        if (!isset($buttons['button'])) {
            $buttons = ['button' => array_values($buttons)];
        }

        $url = 'menu/create';
        if ($conditions) {
            $url = 'menu/addconditional';

            if (isset($conditions['tagId'])) {
                $conditions['tag_id'] = $conditions['tagId'];
                unset($conditions['tagId']);
            }

            if (isset($conditions['platform'])) {
                $conditions['client_platform_type'] = $conditions['platform'];
                unset($conditions['platform']);
            }

            $buttons['matchrule'] = $conditions;
        }

        $res = $this->post($url)
                    ->withJson($buttons)
                    ->getJson();

        $this->throwOfficialError($res);
        return (int) $res->menuId;
    }

    public function delete(int $menuId = 0)
    {
        if ($menuId > 0) {
            $http = $this->post('menu/delconditional')
                         ->withJson(['menuid' => (string) $menuId]);
        } else {
            $http = $this->get('menu/delete');
        }

        $res = $http->getJson();
        $this->throwOfficialError($res);
        return true;
    }

    public function match(string $userId)
    {
        $res = $this->post('menu/trymatch')
                    ->withJson(['user_id' => $userId])
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function get()
    {
        $res = $this->get('menu/get')
                    ->getJson();

        return $this->throwOfficialError($res);
    }
}
