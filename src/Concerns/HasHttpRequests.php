<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-20 16:55:32 +0800
 */
namespace fwkit\Wechat\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

trait HasHttpRequests
{
    public function request(string $method, string $url, array $options, $accessToken = null, bool $returnRaw = false)
    {
        static $client;
        if (!isset($client)) {
            $client = new Client;
        }

        $method = strtoupper($method);
        if (property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        if ($accessToken !== false) {
            if ($accessToken === true) {
                $accessToken = $this->getAccessToken(true);
            } else {
                $accessToken = $accessToken ?: $this->getAccessToken();
            }

            $options['query'] = $options['query'] ?? [];
            $options['query']['access_token'] = $accessToken;
        }

        $response = $client->request($method, $url, $options);
        return $returnRaw ? $response : $this->tryParseResponse($response);
    }

    protected function tryParseResponse(Response $response)
    {
        $res = null;
        if ($response->getStatusCode() === 200) {
            $body = trim($response->getBody());
            if ($body{0} === '<') {
                $res = @simplexml_load_string($body);
            } else {
                $res = @json_decode($body, true);
            }
        }

        return $res ?: $response;
    }
}
