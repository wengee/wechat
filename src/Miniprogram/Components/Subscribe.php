<?php

declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2024-12-04 16:04:58 +0800
 */

namespace fwkit\Wechat\Miniprogram\Components;

use fwkit\Wechat\ComponentBase;

class Subscribe extends ComponentBase
{
    public const VERSION_FORMAL    = 'formal';
    public const VERSION_TRIAL     = 'trial';
    public const VERSION_DEVELOPER = 'developer';

    public function addTemplate(string $tid, array $kidList, string $sceneDesc = '')
    {
        $res = $this->post('wxaapi/newtmpl/addtemplate', [
            'json' => [
                'tid'       => $tid,
                'kidList'   => array_values($kidList),
                'sceneDesc' => $sceneDesc,
            ],
        ]);

        return $this->checkResponse($res);
    }

    public function delTemplate(string $priTmplId)
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
                'ids'   => implode(',', $ids),
                'start' => $start,
                'limit' => $limit,
            ],
        ]);

        return $this->checkResponse($res);
    }

    public function listTemplate()
    {
        $res = $this->get('wxaapi/newtmpl/gettemplate');

        return $this->checkResponse($res);
    }

    public function sendMessage(string $openId, string $templateId, array $data, ?string $page = null, string $version = self::VERSION_FORMAL, string $lang = 'zh_CN')
    {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $data[$key] = ['value' => $value];
            }
        }

        $res = $this->post('cgi-bin/message/subscribe/send', [
            'json' => [
                'touser'            => $openId,
                'template_id'       => $templateId,
                'page'              => $page ?: '',
                'data'              => $data,
                'miniprogram_state' => $version,
                'lang'              => $lang,
            ],
        ]);

        $this->checkResponse($res);

        return true;
    }
}
