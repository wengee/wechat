<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;

class Template extends ComponentBase
{
    public function add(string $id, array $keywordIds)
    {
        $res = $this->post('cgi-bin/wxopen/template/add', [
            'json' => [
                'id' => $id,
                'keyword_id_list' => array_values($keywordIds),
            ],
        ]);

        return $this->checkResponse($res, [
            'template_id' => 'templateId',
        ]);
    }

    public function del(string $templateId)
    {
        $res = $this->post('cgi-bin/wxopen/template/del', [
            'json' => [
                'template_id' => $templateId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function getLibraryById(string $id)
    {
        $res = $this->post('cgi-bin/wxopen/template/library/get', [
            'json' => [
                'id' => $id,
            ],
        ]);

        return $this->checkResponse($res, [
            'keyword_list' => 'keywordList',
            'keyword_id' => 'keywordId',
        ]);
    }

    public function listLibrary(int $offset = 0, int $count = 20)
    {
        $offset = max(0, $offset);
        $count = min(max(1, $count), 20);

        $res = $this->post('cgi-bin/wxopen/template/library/list', [
            'json' => [
                'offset' => $offset,
                'count' => $count,
            ],
        ]);

        return $this->checkResponse($res, [
            'total_count' => 'totalCount',
        ]);
    }

    public function list(int $offset = 0, int $count = 20)
    {
        $offset = max(0, $offset);
        $count = min(max(1, $count), 20);

        $res = $this->post('cgi-bin/wxopen/template/list', [
            'json' => [
                'offset' => $offset,
                'count' => $count,
            ],
        ]);

        return $this->checkResponse($res, [
            'template_id' => 'templateId',
        ]);
    }

    public function send(string $openId, string $templateId, array $data, string $formId, ?string $page = null, ?string $emphasisKeyword = null)
    {
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $data[$key] = ['value' => $value];
            }
        }

        $res = $this->post('cgi-bin/message/wxopen/template/send', [
            'json' => [
                'touser' => $openId,
                'template_id' => $templateId,
                'page' => $page ?: '',
                'form_id' => $formId,
                'data' => $data,
                'emphasis_keyword' => $emphasisKeyword,
            ],
        ]);

        return $this->checkResponse($res, [
            'template_id' => 'templateId',
        ]);
    }

    public function sendUniform(): void
    {
        // TODO
    }
}
