<?php

namespace fwkit\Wechat\Utils;

/**
 * PKCS7Encoder class
 *
 * 提供基于PKCS7算法的加解密接口.
 */
class PKCS7Encoder
{
    /**
     * 对需要加密的明文进行填充补位
     * @param $text 需要进行填充补位操作的明文
     * @return 补齐明文字符串
     */
    public function encode(string $text, int $blockSize = 16)
    {
        $padLen = $blockSize - strlen($text) % $blockSize;
        return $text . str_repeat(chr($padLen), $padLen);
    }

    /**
     * 对解密后的明文进行补位删除
     * @param decrypted 解密后的明文
     * @return 删除填充补位后的明文
     */
    public function decode(string $text, int $blockSize = 16)
    {
        $padLen = ord(substr($text, -1));
        if ($padLen < 1 || $padLen > $blockSize) {
            return $text;
        }

        return substr($text, 0, -$padLen);
    }
}
