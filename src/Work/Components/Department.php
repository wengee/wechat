<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;

class Department extends ComponentBase
{
    public function create(string $name, int $parentId = 1, int $order = -1, int $id = 0)
    {
        $data = ['name' => $name, 'parentid' => $parentId];
        if ($order >= 0) {
            $data['order'] = $order;
        }

        if ($id > 1) {
            $data['id'] = $id;
        }

        $res = $this->post('cgi-bin/department/create', [
            'json' => $data,
        ]);

        $res = $this->checkResponse($res);
        return $res->id;
    }

    public function update(int $id, ?string $name = null, int $parentId = 0, int $order = -1)
    {
        $data = [];
        if ($name !== null) {
            $data['name'] = $name;
        }

        if ($parentId > 0) {
            $data['parentid'] = $parentId;
        }

        if ($order >= 0) {
            $data['order'] = $order;
        }

        if (empty($data)) {
            return false;
        }

        $data['id'] = $id;
        $res = $this->post('cgi-bin/department/update', [
            'json' => $data,
        ]);

        $res = $this->checkResponse($res);
        return true;
    }

    public function del(int $id)
    {
        $res = $this->get('cgi-bin/department/delete', [
            'query' => ['id' => $id],
        ]);

        $res = $this->checkResponse($res);
        return true;
    }

    public function fetch(int $id)
    {
        $res = $this->get('cgi-bin/department/list', [
            'query' => ['id' => $id],
        ]);

        return $this->checkResponse($res, [
            'parentid' => 'parentId',
        ]);
    }
}
