<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-11-15 14:37:49 +0800
 */
namespace fwkit\Wechat\Mp\Components;

use fwkit\Wechat\ComponentBase;

class Template extends ComponentBase
{
    public function setIndustry(int $first, int $second)
    {
        $res = $this->post('cgi-bin/template/api_set_industry', [
            'json' => [
                'industry_id1' => $first,
                'industry_id2' => $second,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function getIndustry()
    {
        $res = $this->get('cgi-bin/template/get_industry');
        return $this->checkResponse($res, [
            'primary_industry' => 'primary',
            'secondary_industry' => 'secondary',
            'first_class' => 'firstClass',
            'second_class' => 'secondClass',
        ]);
    }

    public function add(string $templateShortId)
    {
        $res = $this->post('cgi-bin/template/api_add_template', [
            'json' => [
                'template_id_short' => $templateShortId,
            ],
        ]);

        $res = $this->checkResponse($res);
        return $res->get('template_id');
    }

    public function list()
    {
        $res = $this->get('cgi-bin/template/get_all_private_template');
        return $this->checkResponse($res, [
            'template_list' => 'list',
            'template_id' => 'templateId',
            'primary_industry' => 'primaryIndustry',
            'deputy_industry' => 'deputyIndustry',
        ]);
    }

    public function delete(string $templateId)
    {
        $res = $this->post('cgi-bin/template/del_private_template', [
            'json' => [
                'template_id' => $templateId,
            ],
        ]);

        $this->checkResponse($res);
        return true;
    }

    public function send(string $openId, string $templateId, array $data, bool $oneTime = false)
    {
        $data = $this->transformKeys($data, [
            'miniProgram' => 'miniprogram',
            'appId' => 'appid',
            'pagePath' => 'pagepath',
        ]);

        $data['touser'] = $openId;
        $data['template_id'] = $templateId;

        $url = $oneTime ? 'cgi-bin/message/template/subscribe' : 'cgi-bin/message/template/send';
        $res = $this->post($url, [
            'json' => $data,
        ]);

        $res = $this->checkResponse($res);
        return $res->get('msgid');
    }

    public function subscribeUrl(string $url, string $templateId, int $scene, ?string $reserved = null)
    {
        return sprintf('https://mp.weixin.qq.com/mp/subscribemsg?action=get_confirm&appid=%s&scene=%d&template_id=%s&redirect_url=%s&reserved=%s#wechat_redirect', $this->client->getAppId(), $scene, $templateId, urlencode($url), urlencode($reserved));
    }

    public function sendOneTime(string $openId, string $templateId, array $data)
    {
        return $this->send($openId, $templateId, $data, true);
    }
}
