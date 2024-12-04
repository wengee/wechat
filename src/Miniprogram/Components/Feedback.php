<?php

declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2024-12-04 11:46:28 +0800
 */

namespace fwkit\Wechat\Miniprogram\Components;

use fwkit\Wechat\ComponentBase;

class Feedback extends ComponentBase
{
    public function list(int $type = 0, int $page = 1, int $num = 10)
    {
        $query = ['page' => $page, 'num' => $num];
        if ($type > 0) {
            $query['type'] = $type;
        }

        $res = $this->get('wxaapi/feedback/list', [
            'query' => $query,
        ]);

        return $this->checkResponse($res, [
            'record_id'   => 'recordId',
            'create_time' => 'createTime',
            'head_url'    => 'headUrl',
            'total_num'   => 'totalNum',
        ]);
    }

    public function downloadMedia(string $recordId, string $mediaId)
    {
        $res = $this->get('cgi-bin/media/getfeedbackmedia', [
            'query' => [
                'record_id' => $recordId,
                'media_id'  => $mediaId,
            ],
        ]);

        return $this->checkResponse($res, null, false);
    }
}
