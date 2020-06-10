<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-03 17:15:25 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Comment extends ComponentBase
{
    public function open(int $msgId, int $index = 0)
    {
        $res = $this->post('cgi-bin/comment/open', [
            'json' => [
                'msg_data_id' => $msgId,
                'index' => $index,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function close(int $msgId, int $index = 0)
    {
        $res = $this->post('cgi-bin/comment/close', [
            'json' => [
                'msg_data_id' => $msgId,
                'index' => $index,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function list(int $msgId, int $index = 0, int $begin = 0, int $count = 30, int $type = 0)
    {
        $res = $this->post('cgi-bin/comment/list', [
            'json' => [
                'msg_data_id' => $msgId,
                'index' => $index,
                'begin' => $begin,
                'count' => $count,
                'type' => $type,
            ],
        ]);

        return $this->checkResponse($res, [
            'user_comment_id' => 'commentId',
            'openid' => 'openId',
            'comment_type' => 'commentType',
            'create_time' => 'created',
        ]);
    }

    public function makeElect(int $msgId, int $index, int $commentId)
    {
        $res = $this->post('cgi-bin/comment/markelect', [
            'json' => [
                'msg_data_id' => $msgId,
                'index' => $index,
                'user_comment_id' => $commentId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function unmakeElect(int $msgId, int $index, int $commentId)
    {
        $res = $this->post('cgi-bin/comment/unmarkelect', [
            'json' => [
                'msg_data_id' => $msgId,
                'index' => $index,
                'user_comment_id' => $commentId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function del(int $msgId, int $index, int $commentId)
    {
        $res = $this->post('cgi-bin/comment/delete', [
            'json' => [
                'msg_data_id' => $msgId,
                'index' => $index,
                'user_comment_id' => $commentId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function reply(int $msgId, int $index, int $commentId, string $content)
    {
        $res = $this->post('cgi-bin/comment/reply/add', [
            'json' => [
                'msg_data_id' => $msgId,
                'index' => $index,
                'user_comment_id' => $commentId,
                'content' => $content,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function delReply(int $msgId, int $index, int $commentId)
    {
        $res = $this->post('cgi-bin/comment/reply/delete', [
            'json' => [
                'msg_data_id' => $msgId,
                'index' => $index,
                'user_comment_id' => $commentId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }
}
