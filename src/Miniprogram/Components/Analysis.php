<?php

declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2024-12-04 11:25:20 +0800
 */

namespace fwkit\Wechat\Miniprogram\Components;

use fwkit\Wechat\ComponentBase;

class Analysis extends ComponentBase
{
    public const CYCLE_DAILY   = 1;
    public const CYCLE_WEEKLY  = 2;
    public const CYCLE_MONTHLY = 3;

    public function getVisitTrend(string $beginDate, ?string $endDate = null, $cycle = self::CYCLE_DAILY)
    {
        if (null === $endDate) {
            $endDate = $beginDate;
        }

        $apiUrl = 'datacube/getweanalysisappiddailyvisittrend';
        if (self::CYCLE_WEEKLY === $cycle) {
            $apiUrl = 'datacube/getweanalysisappidweeklyvisittrend';
        } elseif (self::CYCLE_MONTHLY === $cycle) {
            $apiUrl = 'datacube/getweanalysisappidmonthlyvisittrend';
        }

        $res = $this->post($apiUrl, [
            'json' => [
                'begin_date' => $beginDate,
                'end_date'   => $endDate,
            ],
        ]);

        return $this->checkResponse($res, [
            'ref_date'          => 'refDate',
            'session_cnt'       => 'sessionCnt',
            'visit_pv'          => 'visitPv',
            'visit_uv'          => 'visitUv',
            'visit_uv_new'      => 'visitUvNew',
            'stay_time_uv'      => 'stayTimeUv',
            'stay_time_session' => 'stayTimeSession',
            'visit_depth'       => 'visitDepth',
        ]);
    }

    public function getRetainInfo(string $beginDate, ?string $endDate = null, $cycle = self::CYCLE_DAILY)
    {
        if (null === $endDate) {
            $endDate = $beginDate;
        }

        $apiUrl = 'datacube/getweanalysisappiddailyretaininfo';
        if (self::CYCLE_WEEKLY === $cycle) {
            $apiUrl = 'datacube/getweanalysisappidweeklyretaininfo';
        } elseif (self::CYCLE_MONTHLY === $cycle) {
            $apiUrl = 'datacube/getweanalysisappidmonthlyretaininfo';
        }

        $res = $this->post($apiUrl, [
            'json' => [
                'begin_date' => $beginDate,
                'end_date'   => $endDate,
            ],
        ]);

        return $this->checkResponse($res, [
            'ref_date'     => 'refDate',
            'visit_uv'     => 'visitUv',
            'visit_uv_new' => 'visitUvNew',
        ]);
    }

    public function getDailySummary(string $beginDate, ?string $endDate = null)
    {
        if (null === $endDate) {
            $endDate = $beginDate;
        }

        $res = $this->post('datacube/getweanalysisappiddailysummarytrend', [
            'json' => [
                'begin_date' => $beginDate,
                'end_date'   => $endDate,
            ],
        ]);

        return $this->checkResponse($res, [
            'ref_date'    => 'refDate',
            'visit_total' => 'visitTotal',
            'share_pv'    => 'sharePv',
            'share_uv'    => 'shareUv',
        ]);
    }

    public function getVisitPage(string $date)
    {
        $res = $this->post('datacube/getweanalysisappidvisitpage', [
            'json' => [
                'begin_date' => $date,
                'end_date'   => $date,
            ],
        ]);

        return $this->checkResponse($res, [
            'ref_date'         => 'refDate',
            'page_path'        => 'pagePath',
            'page_visit_pv'    => 'pageVisitPv',
            'page_visit_uv'    => 'pageVisitUv',
            'page_staytime_pv' => 'pageStaytimePv',
            'entrypage_pv'     => 'entryPagePv',
            'exitpage_pv'      => 'exitPagePv',
            'page_share_pv'    => 'pageSharePv',
            'page_share_uv'    => 'pageShareUv',
        ]);
    }
}
