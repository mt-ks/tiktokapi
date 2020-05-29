<?php

namespace TikTokAPI\Http;

use TikTokAPI\Constants;
use TikTokAPI\TikTok;

class Request
{
    public TikTok $parent;
    protected string $endpoint;
    protected array $_post = [];
    protected array $_param = [];
    protected array $_header = [];
    protected array $_curl = [];
    protected bool  $disableDefaultParams = false;
    protected int $_base = 0;

    public function __construct($endpoint, TikTok $parent)
    {
        $this->endpoint = $endpoint;
        $this->parent = $parent;
    }


    public function setBaseUrl($i = 0): Request
    {
        $this->_base = $i;
        return $this;
    }

    public function getBaseUrl(): string
    {
        return Constants::API_URL[$this->_base] ?? 'https://api2-19-h2.musical.ly/';
    }

    public function getEndpoint() : string {
        return $this->endpoint;
    }

    public function addPost($key, $value): Request
    {
        $this->_post[$key] = $value;
        return $this;
    }

    public function addParam($key, $value): Request
    {
        $this->_param[$key] = $value;
        return $this;
    }

    public function addHeader($key, $value): Request
    {
        $this->_header[$key] = $value;
        return $this;
    }

    public function addCurl($key, $value): Request
    {
        $this->_curl[$key] = $value;
        return $this;
    }

    /**
     * @param bool $status
     * @return Request
     */
    public function setDisableDefaultParams($status = false): Request
    {
        $this->disableDefaultParams = $status;
        return $this;
    }
    protected function isDisabledDefaultParams() : bool
    {
        return $this->disableDefaultParams;
    }

    public function hasPost(): bool
    {
        return count($this->_post) > 0;
    }

    public function hasHeaders(): bool
    {
        return count($this->_header) > 0;
    }

    public function hasCurlOptions(): bool
    {
        return count($this->_curl) > 0;
    }

    public function hasParams(): bool
    {
        return count($this->_param) > 0;
    }

    public function getRequestPosts()
    {
        return http_build_query($this->_post);
    }

    public function getRequestParams($withQM = true)
    {
        if ($this->disableDefaultParams !== true):
            $this->initDefaultParams();
        endif;
        if ($this->hasParams()) {
            return ($withQM) ? '?' . http_build_query($this->_param) : http_build_query($this->_param);
        }
        return null;
    }

    public function getRequestHeaders($asArray = false) : array
    {
        if ($asArray)
            return $this->_header;

        $headers = [];
        foreach ($this->_header as $key => $value):
            $headers[] = sprintf('%s: %s', $key, $value);
        endforeach;
        return $headers;
    }

    public function getRequestCurl() : array
    {
        return $this->_curl;
    }


    public function initDefaultParams(): void
    {

//        $timestamp = round(microtime(true) * 1000);
//        foreach ($this->defaultParamsList() as $k => $v)
//        {
//            $this->addParam($k,$v);
//        }

        $timestamp = round(microtime(true) * 1000);
        $this
            ->addParam('account_sdk_version', Constants::SDK_VERSION)
            ->addParam('manifest_version_code', Constants::VERSION_CODE)
            ->addParam('_rticket', $timestamp)
            ->addParam('app_language', Constants::LANGUAGE)
            ->addParam('app_type', Constants::APP_TYPE)
            ->addParam('iid', $this->parent->storage->getUser()->deviceInstallID())
            ->addParam('channel', Constants::CHANNEL)
            ->addParam('device_type', Constants::DEVICE)
            ->addParam('language', Constants::LANGUAGE)
            ->addParam('locale', Constants::LANGUAGE)
            ->addParam('resolution', Constants::RESOLUTION)
            ->addParam('openudid', $this->parent->storage->getUser()->deviceOpenUDID())
            ->addParam('update_version_code', Constants::VERSION_CODE)
            ->addParam('ac2', 'wifi')
            ->addParam('sys_region', Constants::REGION)
            ->addParam('os_api', Constants::OS_API)
            ->addParam('uoo', 1)
            ->addParam('is_my_cn', 0)
            ->addParam('timezone_name', 'GMT')
            ->addParam('dpi', '560')
            ->addParam('carrier_region', Constants::REGION)
            ->addParam('ac', 'wifi')
            ->addParam('device_id', $this->parent->storage->getUser()->deviceId())
            ->addParam('pass-route', 1)
            ->addParam('mcc_mnc', 310260)
            ->addParam('os_version', Constants::OS_VERSION)
            ->addParam('timezone_offset', 0)
            ->addParam('version_code', Constants::BUILD_VERSION)
            ->addParam('carrier_region_v2', 310)
            ->addParam('app_name', Constants::APP_NAME)
            ->addParam('ab_version', Constants::TIKTOK_VERSION)
            ->addParam('version_name', Constants::TIKTOK_VERSION)
            ->addParam('device_brand', ucfirst(Constants::PLATFORM))
            ->addParam('ssmix', 'a')
            ->addParam('pass-region', 1)
            ->addParam('device_platform', Constants::PLATFORM)
            ->addParam('build_number', Constants::TIKTOK_VERSION)
            ->addParam('region', Constants::REGION)
            ->addParam('aid', '1233')
            ->addParam('ts', substr($timestamp, 0, -3));
    }

    public function defaultParamsList(): array
    {
        return [
            'filter_warn'           => 0,
            'bid_ad_params'         => '',
            'android_id'            => $this->parent->storage->getUser()->deviceOpenUDID(),
            'ad_personality_mode'   => '1',
            'ts'                    => time(),
            'js_sdk_version'        => '',
            'app_type'              => 'normal',
            'os_api'                => '22',
            'device_type'           => $this->parent->storage->getUser()->deviceType(),
            'ssmix'                 => 'a',
            'manifest_version_code' => '2019091803',
            'dpi'                   => '320',
            'carrier_region'        => 'US',
            'carrier_region_v2'     => '286',
            'app_name'              => 'musical_ly',
            'version_name'          => '13.1.3',
            'timezone_offset'       => '10800',
            'pass-route'            => '1',
            'pass-region'           => '1',
            'is_my_cn'              => 0,
            'fp'                    => '',
            'ac'                    => 'wifi',
            'update_version_code'   => '2019091803',
            'channel'               => 'googleplay',
            '_rticket'              => time() * 1000,
            'device_platform'       => 'android',
            'iid'                   => $this->parent->storage->getUser()->deviceInstallID(),
            'build_number'          => '13.1.3',
            'version_code'          => '990',
            'timezone_name'         => 'Europe/Istanbul',
            'account_region'        => 'V',
            'openudid'              => $this->parent->storage->getUser()->deviceOpenUDID(),
            'device_id'             => $this->parent->storage->getUser()->deviceId(),
            'sys_region'            => 'US',
            'app_language'          => 'us',
            'resolution'            => '720*1280',
            'os_version'            => '5.1.1',
            'device_brand'          => strtolower($this->parent->storage->getUser()->deviceBrand()),
            'language'              => 'us',
            'aid'                   => '1233',
            'mcc_mnc'               => '28601',

        ];
    }

    /**
     * @return HttpClient
     */
    public function execute(): HttpClient
    {
        return new HttpClient($this);
    }


}