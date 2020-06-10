<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;

class User extends ComponentBase
{
    public function create(string $id, string $name, $department, array $data = [])
    {
        if (is_array($department)) {
            $department = array_values(array_map('intval', $department));
        } else {
            $department = [(int) $department];
        }

        $data = $this->transformKeys($data, [
            'isLeader' => 'is_leader_in_dept',
            'avatarMediaId' => 'avatar_mediaid',
            'externalProfile' => 'external_profile',
            'externalPosition' => 'external_position',
            'toInvite' => 'to_invite',
            'externalCorpName' => 'external_corp_name',
            'externalAttr' => 'external_attr',
        ]);

        $data['userid'] = $id;
        $data['name'] = $name;
        $data['department'] = $department;

        $res = $this->post('cgi-bin/user/create', ['json' => $data]);
        $this->checkResponse($res);
        return true;
    }

    public function fetch(string $id)
    {
        $res = $this->get('cgi-bin/user/get', [
            'query' => ['userid' => $id],
        ]);

        return $this->checkResponse($res, [
            'userid' => 'userId',
            'weixinid' => 'weixinId',
            'is_leader_in_dept' => 'isLeader',
            'qr_code' => 'qrCode',
            'external_profile' => 'externalProfile',
            'external_position' => 'externalPosition',
            'external_corp_name' => 'externalCorpName',
            'external_attr' => 'externalAttr',
        ]);
    }

    public function update(string $id, array $data = [])
    {
        if (empty($data)) {
            return false;
        }

        $data = $this->transformKeys($data, [
            'isLeader' => 'is_leader_in_dept',
            'avatarMediaId' => 'avatar_mediaid',
            'externalProfile' => 'external_profile',
            'externalPosition' => 'external_position',
            'toInvite' => 'to_invite',
            'externalCorpName' => 'external_corp_name',
            'externalAttr' => 'external_attr',
        ]);

        $data['userid'] = $id;
        $res = $this->post('cgi-bin/user/update', ['json' => $data]);
        $this->checkResponse($res);
        return true;
    }

    public function del($id)
    {
        if (is_array($id)) {
            $id = array_values($id);
            $res = $this->post('cgi-bin/user/batchdelete', [
                'json' => ['useridlist' => $id],
            ]);
        } else {
            $id = (string) $id;
            $res = $this->get('cgi-bin/user/delete', [
                'query' => ['userid' => $id],
            ]);
        }

        $this->checkResponse($res);
        return true;
    }

    public function fetchList(int $departmentId, bool $fetchChild = true, int $status = 0)
    {
        $res = $this->get('cgi-bin/user/simplelist', [
            'query' => [
                'department_id' => $departmentId,
                'fetch_child' => $fetchChild ? 1 : 0,
                'status' => $status,
            ],
        ]);

        return $this->checkResponse($res, [
            'userlist' => 'userList',
            'userid' => 'userId',
        ]);
    }

    public function fetchAll(int $departmentId, bool $fetchChild = true, int $status = 0)
    {
        $res = $this->get('cgi-bin/user/list', [
            'query' => [
                'department_id' => $departmentId,
                'fetch_child' => $fetchChild ? 1 : 0,
                'status' => $status,
            ],
        ]);

        return $this->checkResponse($res, [
            'userlist' => 'userList',
            'userid' => 'userId',
            'weixinid' => 'weixinId',
            'is_leader_in_dept' => 'isLeader',
            'qr_code' => 'qrCode',
            'external_profile' => 'externalProfile',
            'external_position' => 'externalPosition',
            'external_corp_name' => 'externalCorpName',
            'external_attr' => 'externalAttr',
        ]);
    }

    public function fetchOpenId(string $id)
    {
        $res = $this->post('cgi-bin/user/convert_to_openid', [
            'json' => ['userid' => $id],
        ]);

        $res = $this->checkResponse($res);
        return $res->get('openid');
    }

    public function fetchUserId(string $openId)
    {
        $res = $this->post('qyapi.weixin.qq.com/cgi-bin/user/convert_to_userid', [
            'json' => ['openid' => $openId],
        ]);

        $res = $this->checkResponse($res);
        return $res->get('userid');
    }

    public function authSuccess(string $id)
    {
        $res = $this->get('cgi-bin/user/authsucc', [
            'query' => ['userid' => $id],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function invite(array $user)
    {
        $res = $this->post('cgi-bin/batch/invite', [
            'json' => $user,
        ]);

        return $this->checkResponse($res, [
            'invalidlist' => 'invalidList',
            'invalidparty' => 'invalidParty',
            'invalidtag' => 'invalidTag',
        ]);
    }
}
