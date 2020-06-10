<?php declare(strict_types=1);
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2020-06-04 14:31:37 +0800
 */

namespace fwkit\Wechat\ThirdParty;

interface ThirdClientInterface
{
    public function getAppId();

    public function getAccessToken();

    public function getAuthorizerAccessToken(string $appId);
}
