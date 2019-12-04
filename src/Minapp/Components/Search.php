<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-12-04 14:18:35 +0800
 */
namespace fwkit\Wechat\Minapp\Components;

use fwkit\Wechat\ComponentBase;

class Search extends ComponentBase
{
    public function submitPages(array $pages)
    {
        array_walk($pages, function ($item) {
            return $item + ['path' => '', 'query' => ''];
        });

        $res = $this->post('wxaapi/newtmpl/addtemplate', [
            'json' => [
                'pages' => $pages,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }
}
