<?php


namespace TikTokAPI\Encryption;


class Encryption
{

    /**
     * @param $data
     * @param int $key
     * @return string
     */
    public static function xorEncrypt($data, $key = 5) : string
    {
        $xor = '';
        for ($i = 0, $iMax = strlen($data); $i < $iMax; ++$i) {
            $xor .= bin2hex(chr(ord($data[$i]) ^ $key));
        }

        return $xor;
    }

    public function xGorgonAndKhronos()
    {

    }

}