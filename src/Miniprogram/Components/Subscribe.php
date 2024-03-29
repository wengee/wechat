<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Miniprogram\Components;

use fwkit\Wechat\ComponentBase;

class Subscribe extends ComponentBase
{
    public function add(string $tid, array $kidList, string $sceneDesc = '')
    {
        $res = $this->post('wxaapi/newtmpl/addtemplate', [
            'json' => [
                'tid' => $tid,
                'kidList' => array_values($kidList),
                'sceneDesc' => $sceneDesc,
            ],
        ]);

        return $this->checkResponse($res);
    }

    public function del(string $priTmplId)
    {
        $res = $this->post('wxaapi/newtmpl/deltemplate', [
            'json' => [
                'priTmplId' => $priTmplId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function getCategory()
    {
        $res = $this->get('wxaapi/newtmpl/getcategory');
        return $this->checkResponse($res);
    }

    public function getPubTemplateKeywords(string $tid)
    {
        $res = $this->get('wxaapi/newtmpl/getpubtemplatekeywords', [
            'query' => [
                'tid' => $tid,
            ],
        ]);

        return $this->checkResponse($res);
    }

    public function getPubTemplateTitleList(array $ids, int $start = 0, int $limit = 30)
    {
        $res = $this->get('wxaapi/newtmpl/getpubtemplatetitles', [
            'query' => [
                'ids' => implode(',', $ids),
                'start' => $start,
                'limit' => $limit,
            ],
        ]);

        return $this->checkResponse($res);
    }

    public function list()
    {
        $res = $this->get('wxaapi/newtmpl/gettemplate');
        return $this->checkResponse($res);
    }

    public function send(string $openId, string $templateId, array $data, ?string $page = null)
    {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $data[$key] = ['value' => $value];
            }
        }

        $res = $this->post('cgi-bin/message/subscribe/send', [
            'json' => [
                'touser' => $openId,
                'template_id' => $templateId,
                'page' => $page ?: '',
                'data' => $data,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }
}
