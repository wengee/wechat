<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Tag extends ComponentBase
{
    public function create(string $tagName)
    {
        $res = $this->post('cgi-bin/tags/create', [
            'json' => [
                'tag' => ['name' => $tagName],
            ],
        ]);

        $res = $this->checkResponse($res);
        return $res->get('tag.id');
    }

    public function fetchAll()
    {
        $res = $this->get('cgi-bin/tags/get');
        return $this->checkResponse($res);
    }

    public function update(int $tagId, string $tagName)
    {
        $res = $this->post('cgi-bin/tags/update', [
            'json' => [
                'tag' => ['id' => $tagId, 'name' => $tagName],
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function del(int $tagId)
    {
        $res = $this->post('cgi-bin/tags/delete', [
            'json' => [
                'tag' => ['id' => $tagId],
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function tagging($openId, int $tagId)
    {
        if (!is_array($openId)) {
            $openId = [$openId];
        }

        $res = $this->post('cgi-bin/tags/members/batchtagging', [
            'json' => [
                'openid_list' => $openId,
                'tagid' => $tagId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function untagging($openId, int $tagId)
    {
        if (!is_array($openId)) {
            $openId = [$openId];
        }

        $res = $this->post('cgi-bin/tags/members/batchuntagging', [
            'json' => [
                'openid_list' => $openId,
                'tagid' => $tagId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function fetch(string $openId)
    {
        $res = $this->post('cgi-bin/tags/getidlist', [
            'json' => ['openid' => $openId],
        ]);

        return $this->checkResponse($res, [
            'tagid_list' => 'tagIdList',
        ]);
    }

    public function blacklist(?string $nextOpenId = null)
    {
        $res = $this->post('cgi-bin/tags/members/getblacklist', [
            'json' => ['begin_openid' => $nextOpenId],
        ]);

        return $this->checkResponse($res, [
            'openid' => 'openId',
            'next_openid' => 'nextOpenId',
        ]);
    }

    public function black($openId)
    {
        if (!is_array($openId)) {
            $openId = [$openId];
        }

        $res = $this->post('cgi-bin/tags/members/batchblacklist', [
            'json' => ['openid_list' => $openId],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function unblack($openId)
    {
        if (!is_array($openId)) {
            $openId = [$openId];
        }

        $res = $this->post('cgi-bin/tags/members/batchunblacklist', [
            'json' => ['openid_list' => $openId],
        ]);

        $this->checkResponse($res);
        return true;
    }
}
