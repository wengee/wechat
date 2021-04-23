<?php
declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2021-04-23 16:47:41 +0800
 */

namespace fwkit\Wechat\Mp\Components;

use BadMethodCallException;
use fwkit\Wechat\ComponentBase;

/**
 * @method mixed getUserSummary(string $startDate, string $endDate)
 * @method mixed getUserCumulate(string $startDate, string $endDate)
 * @method mixed getArticleSummary(string $startDate, string $endDate)
 * @method mixed getArticleTotal(string $startDate, string $endDate)
 * @method mixed getUserRead(string $startDate, string $endDate)
 * @method mixed getUserReadHour(string $startDate, string $endDate)
 * @method mixed getUserShare(string $startDate, string $endDate)
 * @method mixed getUserShareHour(string $startDate, string $endDate)
 */
class Statis extends ComponentBase
{
    private $apiList = [
        // 用户分析
        'getUserSummary'          => 'datacube/getusersummary',
        'getUserCumulate'         => 'datacube/getusercumulate',

        // 图文分析
        'getArticleSummary'       => 'datacube/getarticlesummary',
        'getArticleTotal'         => 'datacube/getarticletotal',
        'getUserRead'             => 'datacube/getuserread',
        'getUserReadHour'         => 'datacube/getuserreadhour',
        'getUserShare'            => 'datacube/getusershare',
        'getUserShareHour'        => 'datacube/getusersharehour',

        // 消息分析
        'getUpstreamMsg'          => 'datacube/getupstreammsg',
        'getUpstreamMsgHour'      => 'datacube/getupstreammsghour',
        'getUpstreamMsgWeek'      => 'datacube/getupstreammsgweek',
        'getUpstreamMsgMonth'     => 'datacube/getupstreammsgmonth',
        'getUpstreamMsgDist'      => 'datacube/getupstreammsgdist',
        'getUpstreamMsgDistWeek'  => 'datacube/getupstreammsgdistweek',
        'getUpstreamMsgDistMonth' => 'datacube/getupstreammsgdistmonth',

        // 接口分析
        'getInterfaceSummary'     => 'datacube/getinterfacesummary',
        'getInterfaceSummaryHour' => 'datacube/getinterfacesummaryhour',
    ];

    public function __call(string $method, array $params)
    {
        $api = $this->apiList[$method] ?? null;
        if (!$api) {
            throw new BadMethodCallException("Call to undefined method [{$method}]");
        }

        $startDate = (string) $params[0] ?? '';
        $endDate   = (string) $params[1] ?? '';

        return $this->fetchFromApi($api, $startDate, $endDate);
    }

    private function fetchFromApi(string $api, string $beginDate, string $endDate)
    {
        $res = $this->post($api, [
            'json' => [
                'begin_date' => $beginDate,
                'end_date'   => $endDate,
            ],
        ]);

        return $this->checkResponse($res);
    }
}
