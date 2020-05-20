<?php


namespace TikTokAPI;


class UserAgentBuilder
{

    /**
     * @return string
     */
    public static function build() : string
    {
        $randomPhone         = self::randomPhoneName();
        $androidSDKINT       = self::androidVersion();
        return sprintf('com.zhiliaoapp.musically/%s (Linux; U; Android %s; tr_TR; %s; Build/M1AJB; Cronet/58.0.2991.0)',Constants::MANIFEST_VERSION_CODE,$androidSDKINT,$randomPhone['model_number']);
    }

    /**
     * @return string
     */
    public static function cpuHardware(): string
    {
        $hardware = ['exynos9610','samsungexynos8890','qcom','hi3660','hi6250'];
        return $hardware[array_rand($hardware)];
    }

    /**
     * @return string
     */
    public static function androidVersion(): string
    {
        $versions = ['5.1', '6.0', '7.0', '7.1', '8.0', '8.1', '9'];
        return $versions[array_rand($versions)];
    }

    /**
     * @return array|string[]
     */
    public static function buildDPI(): array
    {
        $dpi = [
            [
                'dpi' => '380dpi',
                'wh'  => '1080x1920'
            ],
            [
                'dpi' => '640dpi',
                'wh'  => '1440x2392',
            ],
            [
                'dpi' => '640dpi',
                'wh'  => '1440x2560'
            ],
            [
                'dpi' => '431dpi',
                'wh'  => '1080x2280'
            ]
        ];
        return $dpi[array_rand($dpi)];
    }

    /**
     * @param $android
     * @return int
     */
    public static function apiLevel($android): int
    {
        switch ($android) {
            case '5.1':
                return 22;
                break;
            case '6.0':
                return 23;
                break;
            case '7.0':
                return 24;
                break;
            case '7.1':
                return 25;
                break;
            case '8.0':
                return 26;
                break;
            case '8.1':
                return 27;
                break;
            case '9':
                return 28;
                break;
        }
        return 28;
    }

    /**
     * @return array
     */
    public static function randomPhoneName(): array
    {
        $phones = self::phones();

        $selectPhone = array_rand($phones);
        $selectModel = array_rand($phones[$selectPhone]);
        $selectModelNumber = $phones[$selectPhone][$selectModel][array_rand($phones[$selectPhone][$selectModel])];

        return [
            'phone_brand' => $selectPhone,
            'phone_model' => $selectModel,
            'model_number' => $selectModelNumber
        ];
    }

    /**
     * @return array|string[]
     */
    public static function phones(): array
    {
        return [
            'SAMSUNG' => [
                'GALAXY A9' => [
                    'SM-A9000',
                    'SM-A9100',
                    'SM-A910F',
                ],
                'GALAXY A8' => [
                    'SM-A810S',
                    'SM-A8000',
                    'SM-A800F',
                    'SM-A800I',
                    'SM-A800J',
                    'SM-A800S',
                    'SM-A800YZ'
                ]
            ]
        ];
    }

}