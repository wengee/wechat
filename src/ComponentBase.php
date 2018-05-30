<?php

namespace fwkit\Wechat;

use fwkit\Collection;

abstract class ComponentBase
{
    protected $client;

    protected $config;

    public function setClient(ClientBase $client)
    {
        $this->client = $client;
        $this->config = $client->getConfig();
    }

    protected function get(string $url, $accessToken = null)
    {
        return $this->client->request('GET', $url, $accessToken);
    }

    protected function post(string $url, $accessToken = null)
    {
        return $this->client->request('POST', $url, $accessToken);
    }

    protected function request(string $method, string $url, $accessToken = null)
    {
        return $this->client->request($method, $url, $accessToken);
    }

    protected function throwOfficialError(&$res)
    {
        if (!is_array($res)) {
            throw new OfficialError('An unknown error occurred.');
        } elseif (!empty($res['errcode'])) {
            throw new OfficialError($res['errmsg'], $res['errcode']);
        }

        $res = new Collection($res, Collection::ALPHA_ONLY);
        return $res;
    }
}
