<?php

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class User extends ComponentBase
{
    public function remark(string $openId, string $remark)
    {
        $res = $this->post('user/info/updateremark')
                    ->withJson(['openid' => $openId, 'remark' => $remark])
                    ->getJson();

        $this->throwOfficialError($res);
        return true;
    }

    public function get(string $openId, string $lang = 'zh_CN')
    {
        $res = $this->get('user/info')
                    ->withQuery(['openid' => $openId, 'lang' => $lang])
                    ->getJson();

        return $this->throwOfficialError($res);
    }

    public function batchGet(array $users, $lang = 'zh_CN')
    {
        array_walk($users, function (&$item) use ($lang) {
            if (!is_array($item)) {
                $item = ['openid' => $item, 'lang' => $lang];
            }
        });

        $userList = array_values($users);
        $res = $this->post('user/info/batchget')
                    ->withJson(['user_list' => $userList])
                    ->getJson();

        $this->throwOfficialError($res);
        return $res->userInfoList;
    }

    public function list(string $nextOpenId = '')
    {
        $res = $this->get('user/get')
                    ->withQuery(['next_openid' => $nextOpenId])
                    ->getJson();

        return $this->throwOfficialError($res);
    }
}
