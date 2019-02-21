<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2019-02-21 15:21:48 +0800
 */
namespace fwkit\Wechat\Concerns;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

trait HasHttpRequests
{
    protected static $defaultHandler;

    public static function setDefaultHandler($handler)
    {
        if (is_callable($handler)) {
            self::$defaultHandler = $handler;
        } elseif (is_string($handler) && class_exists($handler)) {
            self::$defaultHandler = new $handler;
        }
    }

    public function request(string $method, string $url, array $options, $accessToken = null, $dataType = 'auto')
    {
        static $client;
        if (!isset($client)) {
            $client = new Client([
                'handler' => self::$defaultHandler,
            ]);
        }

        $method = strtoupper($method);
        if (!preg_match('#^https?://.+#i', $url) && property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
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

        if (isset($options['withCert'])) {
            $withCert = $options['withCert'];
            unset($options['withCert']);

            if ($withCert) {
                $options['cert'] = $this->sslCert;
                $options['ssl_key'] = $this->sslKey;
            }
        }

        // 妈妈咪呀，微信不认识 \uxxxx的中文编码
        if (isset($options['json'])) {
            $options['body'] = json_encode($options['json'], JSON_UNESCAPED_UNICODE);
            unset($options['json']);
        }

        $response = $client->request($method, $url, $options);
        return $this->parseResponse($response);
    }

    protected function parseResponse(Response $response, $dataType = 'auto')
    {
        if ($dataType === 'raw' || empty($dataType)) {
            return $response;
        }

        if ($response->getStatusCode() !== 200) {
            throw new \Exception(
                $response->getBody(),
                $response->getStatusCode()
            );
        }

        $res = null;
        $body = trim($response->getBody());
        if ($body) {
            if ($dataType === 'xml' || ($dataType === 'auto' && $body{0} === '<')) {
                $backup = libxml_disable_entity_loader(true);
                $res = @simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);
                $res = $res ? json_decode(json_encode($res), true) : null;

                libxml_disable_entity_loader($backup);
            } elseif ($dataType === 'json' || $dataType === 'auto') {
                $res = @json_decode($body, true);
            }
        }

        return ($dataType === 'auto') ? ($res ?: $response) : $res;
    }
}
