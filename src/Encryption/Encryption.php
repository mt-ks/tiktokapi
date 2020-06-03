<?php


namespace TikTokAPI\Encryption;

use TikTokAPI\Constants;
use TikTokAPI\Device\UserAgentBuilder;

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

    public static function deviceId()
    {
        $megaRandomHash = md5(number_format(microtime(true), 7, '', ''));
        return substr($megaRandomHash, 16);
    }

    public static  function generateRandomString($length = 16) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function generateUUID(
        $keepDashes = true
    )
    {
        $uuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
        return $keepDashes ? $uuid : str_replace('-', '', $uuid);
    }

    public static function randomMac()
    {
        return implode(':', str_split(str_pad(base_convert(mt_rand(0, 0xffffff), 10, 16) . base_convert(mt_rand(0, 0xffffff), 10, 16), 12), 2));
    }

    public static function deviceRegisterData(): array
    {
        $device   = UserAgentBuilder::devices();
        $randDevice = $device[array_rand($device)];
        $carriers = UserAgentBuilder::carriers();
        $randCarrier = $carriers[array_rand($carriers)];
        $appData = [
            'magic_tag' => 'ss_app_log',
            'header'    => [
                'display_name'          => 'TikTok',
                'update_version_code'   => Constants::VERSION_CODE,
                'manifest_version_code' => Constants::VERSION_CODE,
                'aid'                   => "1233",
                'channel'               => 'googleplay',
                'appkey'                => '5559e28267e58eb4c1000012',
                'package'               => 'com.zhiliaoapp.musically',
                'app_version'           => Constants::TIKTOK_VERSION,
                'version_code'          => Constants::VERSION_CODE,
                'sdk_version'           => '2.5.5.8',
                'os'                    => 'Android',
                'os_version'            => '10',
                'os_api'                => '29',
                'device_model'          => $randDevice[1],
                'device_brand'          => $randDevice[0],
                'cpu_abi'               => 'arm64-v8a',
                'release_build'         => 'eaeeb2f_20190929',
                'density_dpi'           => $randDevice[3],
                'display_density'       => 'mdpi',
                'resolution'            => str_replace(["X","x"],"*",$randDevice[2]),
                'language'              => 'tr',
                'mc'                    => self::randomMac(),
                'timezone'              => 0,
                'access'                => 'wifi',
                'not_request_sender'    => 0,
                'carrier'               => $randCarrier[2],
                'mcc_mnc'               => $randCarrier[0] . $randCarrier[1],
                'google_aid'            => self::generateUUID(),
                'openudid'              => self::deviceId(),
                'clientudid'            => self::generateUUID(),
                'sim_serial_number'     => [],
                'tz_name'               => 'Europe/Istanbul',
                'tz_offset'             => 10800,
                'sim_region'            => 'tr'
            ],
            "_gen_time" => time()
        ];
        return [
          'encoded' => json_encode($appData, JSON_THROW_ON_ERROR),
          'data'    => $appData,
          'ua'      => "com.zhiliaoapp.musically/2019092901 (Linux; U; Android 10; tr_TR; $randDevice[1]; Build/QP1A.190711.020; Cronet/58.0.2991.0)",
          'carrier' => $randCarrier
        ];
    }
}
