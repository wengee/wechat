<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2023-06-08 10:24:28 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Publish extends ComponentBase
{
    public function submit(string $mediaId)
    {
        $res = $this->post('cgi-bin/freepublish/submit', [
            'json' => [
                'media_id' => $mediaId,
            ],
        ]);

        return $this->checkResponse($res, [
            'publish_id'  => 'publishId',
            'msg_data_id' => 'msgDataId',
        ]);
    }

    public function status(string $publishId)
    {
        $res = $this->post('cgi-bin/freepublish/get', [
            'json' => [
                'publish_id' => $publishId,
            ],
        ]);

        return $this->checkResponse($res, [
            'publish_id'     => 'publishId',
            'publish_status' => 'msgDataId',
            'article_id'     => 'articleId',
            'article_detail' => 'articleDetail',
            'article_url'    => 'articleUrl',
            'fail_idx'       => 'failIdx',
        ]);
    }

    public function delete(string $articleId, int $index = 1)
    {
        $res = $this->post('cgi-bin/freepublish/delete', [
            'json' => [
                'article_id' => $articleId,
                'index'      => $index,
            ],
        ]);

        $this->checkResponse($res);

        return true;
    }

    public function detail(string $articleId)
    {
        $res = $this->post('cgi-bin/freepublish/getarticle', [
            'json' => [
                'article_id' => $articleId,
            ],
        ]);

        return $this->checkResponse($res, [
            'news_item'             => 'newsItem',
            'content_source_url'    => 'contentSourceUrl',
            'thumb_media_id'        => 'thumbMediaId',
            'show_cover_pic'        => 'showCoverPic',
            'need_open_comment'     => 'needOpenComment',
            'only_fans_can_comment' => 'onlyFansComment',
            'is_deleted'            => 'isDeleted',
        ]);
    }

    public function list(int $offset = 0, int $count = 20, bool $noContent = true)
    {
        $res = $this->post('cgi-bin/freepublish/batchget', [
            'json' => [
                'offset'     => $offset,
                'count'      => $count,
                'no_content' => $noContent ? 1 : 0,
            ],
        ]);

        return $this->checkResponse($res, [
            'total_count'           => 'totalCount',
            'item_count'            => 'itemCount',
            'article_id'            => 'articleId',
            'news_item'             => 'newsItem',
            'content_source_url'    => 'contentSourceUrl',
            'thumb_media_id'        => 'thumbMediaId',
            'show_cover_pic'        => 'showCoverPic',
            'need_open_comment'     => 'needOpenComment',
            'only_fans_can_comment' => 'onlyFansComment',
            'is_deleted'            => 'isDeleted',
            'update_time'           => 'updateTime',
        ]);
    }
}
