<?php
/**
 * @author   Fung Wing Kit <wengee@gmail.com>
 * @version  2018-11-01 17:51:10 +0800
 */
namespace fwkit\Wechat\Utils;

/**
 * Prpcrypt class
 *
 * 提供接收和推送给公众平台消息的加解密接口.
 */
class Prpcrypt
{
    public $key;

    public function __construct(string $k)
    {
        $this->key = base64_decode($k . '=');
    }

    /**
     * 对明文进行加密
     * @param string $text 需要加密的明文
     * @return string 加密后的密文
     */
    public function encrypt(string $text, string $appId)
    {
        $toEncodeData = openssl_random_pseudo_bytes(16) . pack('N', strlen($text)) . $text . $appId;
        $toEncodeData = PKCS7Encoder::encode($toEncodeData, 32);

        $encrypted = openssl_encrypt(
            $toEncodeData,
            'AES-256-CBC',
            $this->key,
            OPENSSL_ZERO_PADDING,
            substr($this->key, 0, 16)
        );

        if ($encrypted === false) {
            return [ErrorCode::ENCRYPT_AES_ERROR, null];
        }

        return [ErrorCode::OK, $encrypted];
    }

    /**
     * 对密文进行解密
     * @param string $encrypted 需要解密的密文
     * @return string 解密得到的明文
     */
    public function decrypt(string $encrypted, string $appId)
    {
        $decrypted = openssl_decrypt(
            $encrypted,
            'AES-256-CBC',
            $this->key,
            OPENSSL_ZERO_PADDING,
            substr($this->key, 0, 16)
        );

        if ($decrypted === false) {
            return [ErrorCode::DECRYPT_AES_ERROR, null];
        }

        $decrypted = PKCS7Encoder::decode($decrypted, 32);

        try {
            $content = substr($decrypted, 16);
            $len = unpack('N', substr($content, 0, 4));
            $len = $len[1];
            $xml = substr($content, 4, $len);
            $fromAppId = substr($content, $len + 4);
        } catch (\Exception $e) {
            return [ErrorCode::ILLEGAL_BUFFER, null];
        }

        if ($fromAppId !== $appId) {
            return [ErrorCode::VALIDATE_APPID_ERROR, null];
        }

        return [ErrorCode::OK, $xml];
    }
}
