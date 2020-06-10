<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;

class Tag extends ComponentBase
{
    public function create(string $name, int $id = 0)
    {
        $data = ['tagname' => $name];
        if ($id > 0) {
            $data['tagid'] = $id;
        }

        $res = $this->post('cgi-bin/tag/create', ['json' => $data]);
        $res = $this->checkResponse($res, ['tagid' => 'tagId']);
        return $res->tagId;
    }

    public function update(int $id, string $name)
    {
        $res = $this->post('cgi-bin/tag/update', [
            'json' => [
                'tagid' => $id,
                'tagname' => $name,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function del(int $id)
    {
        $res = $this->get('cgi-bin/tag/delete', [
            'query' => ['tagid' => $id],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function fetchAll()
    {
        $res = $this->get('cgi-bin/tag/list');
        return $this->checkResponse($res, [
            'taglist' => 'tagList',
            'tagid' => 'tagId',
            'tagname' => 'tagName',
        ]);
    }

    public function fetch(int $id)
    {
        $res = $this->get('cgi-bin/tag/get', [
            'query' => ['tagid' => $id],
        ]);

        return $this->checkResponse($res, [
            'tagname' => 'tagName',
            'userlist' => 'userList',
            'userid' => 'userId',
            'partylist' => 'partyList',
        ]);
    }

    public function tagging(int $id, $user)
    {
        $data = $this->formatUser($user);
        $data['tagid'] = $id;
        $res = $this->post('cgi-bin/tag/addtagusers', ['json' => $data]);
        return $this->checkResponse($res, [
            'invalidlist' => 'invalidList',
            'invalidparty' => 'invalidParty',
        ]);
    }

    public function untagging(int $id, $user)
    {
        $data = $this->formatUser($user);
        $data['tagid'] = $id;
        $res = $this->post('cgi-bin/tag/deltagusers', ['json' => $data]);
        return $this->checkResponse($res, [
            'invalidlist' => 'invalidList',
            'invalidparty' => 'invalidParty',
        ]);
    }

    protected function formatUser($user): array
    {
        if (is_string($user)) {
            return ['userlist' => [$user]];
        } elseif (is_int($user)) {
            return ['partylist' => [$user]];
        } elseif (is_array($user)) {
            $ret = [];
            if (isset($user['user'])) {
                $ret['userlist'] = array_values((array) $user['user']);
            }

            if (isset($user['party'])) {
                $ret['partylist'] = array_values((array) $user['party']);
            }

            return $ret;
        }

        return [];
    }
}
