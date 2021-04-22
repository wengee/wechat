<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-04-22 18:36:37 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Notification extends ComponentBase
{
    public function getCategory()
    {
        $res = $this->get('wxaapi/newtmpl/getcategory');
        $this->checkResponse($res);

        return $res->get('data');
    }

    public function getTemplateList()
    {
        $res = $this->get('wxaapi/newtmpl/gettemplate');
        $this->checkResponse($res);

        return $res->get('data');
    }

    public function addTemplate(string $tid, array $kidList, string $sceneDesc)
    {
        $res = $this->post('wxaapi/newtmpl/addtemplate', [
            'json' => [
                'tid'       => $tid,
                'kidList'   => $kidList,
                'sceneDesc' => $sceneDesc,
            ],
        ]);

        $res = $this->checkResponse($res);

        return $res->get('priTmplId');
    }

    public function deleteTemplate(string $priTmplId)
    {
        $res = $this->post('wxaapi/newtmpl/deltemplate', [
            'json' => [
                'priTmplId' => $priTmplId,
            ],
        ]);

        $this->checkResponse($res);

        return true;
    }

    /**
     * @param array|string $ids
     */
    public function getPubTemplateTitleList($ids, int $start = 0, int $limit = 20)
    {
        $ids = is_array($ids) ? implode(',', $ids) : strval($ids);

        $res = $this->get('wxaapi/newtmpl/getpubtemplatetitles', [
            'query' => [
                'ids'   => $ids,
                'start' => $start,
                'limit' => $limit,
            ],
        ]);

        return $this->checkResponse($res);
    }

    public function getPubTemplateKeyWordsById(string $tid)
    {
        $res = $this->get('wxaapi/newtmpl/getpubtemplatekeywords', [
            'query' => [
                'tid' => $tid,
            ],
        ]);

        return $this->checkResponse($res);
    }

    public function send(string $openId, string $templateId, array $data, ?string $page = null, ?array $miniProgram = null)
    {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $data[$key] = ['value' => $value];
            }
        }

        $res = $this->post('cgi-bin/message/subscribe/bizsend', [
            'json' => [
                'touser'      => $openId,
                'template_id' => $templateId,
                'page'        => $page ?: '',
                'miniprogram' => $miniProgram,
                'data'        => $data,
            ],
        ]);

        $this->checkResponse($res);

        return true;
    }
}
