<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class User extends ComponentBase
{
    public function fetch($user, string $lang = 'zh_CN')
    {
        $data = [];
        if (is_array($user)) {
            $method = 'POST';
            $url = 'cgi-bin/user/info/batchget';
            $data['user_list'] = [];
            foreach ($user as $value) {
                if (is_array($value)) {
                    $data['user_list'][] = $value;
                } else {
                    $data['user_list'][] = [
                        'openid' => (string) $value,
                        'lang' => $lang,
                    ];
                }
            }

            $params = ['json' => $data];
        } else {
            $method = 'GET';
            $url = 'cgi-bin/user/info';
            $data['openid'] = (string) $user;
            $data['lang'] = $lang;

            $params = ['query' => $data];
        }

        $res = $this->request($method, $url, $params);
        return $this->checkResponse($res, [
            'user_info_list' => 'list',
            'openid' => 'openId',
            'headimgurl' => 'headImgUrl',
            'unionid' => 'unionId',
            'groupid' => 'groupId',
            'tagid_list' => 'tagIdList',
            'subscribe_time' => 'subscribeTime',
            'subscribe_scene' => 'subscribeScene',
            'qr_scene' => 'qrScene',
            'qr_scene_str' => 'qrSceneStr',
        ]);
    }

    public function fetchAll(?string $nextOpenId = null, int $tagId = 0)
    {
        $data = ['next_openid' => $nextOpenId];
        if ($tagId > 0) {
            $method = 'POST';
            $url = 'cgi-bin/user/tag/get';
            $data['tagid'] = $tagId;

            $params = ['json' => $data];
        } else {
            $method = 'GET';
            $url = 'cgi-bin/user/get';

            $params = ['query' => $data];
        }

        $res = $this->request($method, $url, $params);
        return $this->checkResponse($res, [
            'openid' => 'openId',
            'next_openid' => 'nextOpenId',
        ]);
    }

    public function remark(string $openId, string $remark = '')
    {
        $res = $this->post('cgi-bin/user/info/updateremark', [
            'json' => [
                'openid' => $openId,
                'remark' => $remark,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }
}
