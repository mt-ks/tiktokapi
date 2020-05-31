<?php


namespace TikTokAPI\Encryption;

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
                'update_version_code'   => "2019091803",
                'manifest_version_code' => "2019091803",
                'aid'                   => "1233",
                'channel'               => 'googleplay',
                'appkey'                => '5559e28267e58eb4c1000012',
                'package'               => 'com.zhiliaoapp.musically',
                'app_version'           => '13.1.3',
                'version_code'          => '2019091803',
                'sdk_version'           => '2.5.5.8',
                'os'                    => 'Android',
                'os_version'            => '7.1.2',
                'os_api'                => '25',
                'device_model'          => $randDevice[1],
                'device_brand'          => $randDevice[0],
                'cpu_abi'               => 'arm64-v8a',
                'release_build'         => 'eaeeb2f_20190929',
                'density_dpi'           => $randDevice[3],
                'display_density'       => 'mdpi',
                'resolution'            => $randDevice[2],
                'language'              => 'en',
                'mc'                    => self::randomMac(),
                'timezone'              => 1,
                'access'                => 'wifi',
                'not_request_sender'    => 0,
                'carrier'               => $randCarrier[2],
                'mcc_mnc'               => $randCarrier[0] . $randCarrier[1],
                'google_aid'            => self::generateUUID(),
                'openudid'              => self::deviceId(),
                'clientudid'            => self::generateUUID(),
                'sim_serial_number'     => [],
                'tz_name'               => 'America/New_York',
                'tz_offset'             => 0,
                'sim_region'            => 'us'
            ],
            "_gen_time" => time()
        ];
        return [
          'encoded' => json_encode($appData, JSON_THROW_ON_ERROR),
          'data'    => $appData,
          'ua'      => "com.zhiliaoapp.musically/2019091803 (Linux; U; Android 7.1.2 en; $randDevice[1]; Build/$randDevice[1]; Cronet/58.0.2991.0)",
          'carrier' => $randCarrier
        ];
    }
}
