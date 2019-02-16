<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-16 10:58:18 +0800
 */
namespace fwkit\Wechat\Work\Components;

use fwkit\Wechat\ComponentBase;

class Agent extends ComponentBase
{
    public function fetch(int $id)
    {
        $res = $this->get('cgi-bin/agent/get', [
            'query' => [
                'agentid' => $id,
            ],
        ]);

        return $this->checkResponse($res, [
            'agentid' => 'agentId',
            'square_logo_url' => 'squareLogoUrl',
            'round_logo_url' => 'roundLogoUrl',
            'allow_userinfos' => 'allowUserInfos',
            'allow_partys' => 'allowParties',
            'allow_tags' => 'allowTags',
            'redirect_domain' => 'domain',
            'report_location_flag' => 'reportLocation',
            'home_url' => 'homeUrl',
            'isreportuser' => 'isReportUser',
            'isreportenter' => 'isReportEnter',
            'chat_extension_url' => 'chatExtUrl',
            'userid' => 'userId',
            'partyid' => 'partyId',
            'tagid' => 'tagId',
        ]);
    }

    public function fetchAll()
    {
        $res = $this->get('cgi-bin/agent/list');

        return $this->checkResponse($res, [
            'agentlist' => 'agentList',
            'agentid' => 'agentId',
            'square_logo_url' => 'squareLogoUrl',
            'round_logo_url' => 'roundLogoUrl',
        ]);
    }

    public function update(int $agentId, array $data)
    {
        $data = $this->transformKeys($data, [
            'reportLocation' => 'report_location_flag',
            'logoMediaId' => 'logo_mediaid',
            'domain' => 'redirect_domain',
            'isReportUser' => 'isreportuser',
            'isReportEnter' => 'isreportenter',
            'homeUrl' => 'home_url',
            'chatExtUrl' => 'chat_extension_url',
        ]);

        $data['agentid'] = $agentId;
        $res = $this->post('cgi-bin/agent/set', ['json' => $data]);
        $this->checkResponse($res);
        return true;
    }
}
