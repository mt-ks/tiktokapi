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
    protected bool $needsCookie = true;
    protected bool $isDisabledTokens = false;
    protected ?string $postPayload = null;

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

    public function getEndpoint() : string
    {
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



    public function setNeedsCookie(bool $bool): Request
    {
        $this->needsCookie = $bool;
        return $this;
    }

    public function getNeedsCookie() : bool
    {
        return $this->needsCookie;
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
//            $this->init99Params();
            $this->initDefaultParams();
        endif;
        if ($this->hasParams()) {
            return ($withQM) ? '?' . http_build_query($this->_param) : http_build_query($this->_param);
        }
        return null;
    }

    public function getRequestHeaders($asArray = false) : array
    {
        if ($asArray) {
            return $this->_header;
        }

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
        $timestamp = round(microtime(true) * 1000);

        $this->addParam('manifest_version_code',Constants::VERSION_CODE);
        $this->addParam('_rticket',$timestamp);
        $this->addParam('app_language','tr');
        $this->addParam('app_type','normal');
        $this->addParam('iid',$this->parent->storage->getUser()->getInstallId());
        $this->addParam('channel','googleplay');
        $this->addParam('device_type',$this->parent->storage->getUser()->getDeviceType());
        $this->addParam('language','tr');
        $this->addParam('locale','tr-TR');
        $this->addParam('resolution',$this->parent->storage->getUser()->getResolution());
        $this->addParam('openudid',$this->parent->storage->getUser()->getOpenudid());
        $this->addParam('update_version_code',Constants::UPDATE_VERSION_CODE);
        $this->addParam('ac2','wifi');
        $this->addParam('sys_region','TR');
        $this->addParam('os_api','29');
        $this->addParam('uoo','0');
        $this->addParam('is_my_cn','0');
        $this->addParam('timezone_name','Europe/Istanbul');
        $this->addParam('dpi',$this->parent->storage->getUser()->getDpi());
        $this->addParam('carrier_region','TR');
        $this->addParam('ac','wifi');
        $this->addParam('device_id',$this->parent->storage->getUser()->getDeviceId());
        $this->addParam('pass-route','1');
        $this->addParam('mcc_mnc',$this->parent->storage->getUser()->getMccMnc());
        $this->addParam('os_version','10');
        $this->addParam('timezone_offset','10800');
        $this->addParam('version_code',Constants::BUILD_VERSION);
        $this->addParam('carrier_region_v2',$this->parent->storage->getUser()->getCarrierRegionV2());
        $this->addParam('app_name','musical_ly');
        $this->addParam('ab_version',Constants::TIKTOK_VERSION);
        $this->addParam('version_name',Constants::TIKTOK_VERSION);
        $this->addParam('device_brand',$this->parent->storage->getUser()->getDeviceBrand());
        $this->addParam('ssmix','a');
        $this->addParam('pass-region','1');
        $this->addParam('device_platform','android');
        $this->addParam('build_number',Constants::TIKTOK_VERSION);
        $this->addParam('region','TR');
        $this->addParam('aid','1233');
        $this->addParam('ts',substr($timestamp, 0, -3));

    }


    public function init99Params() : void
    {
        $params = [
            'filter_warn'           => 0,
            'bid_ad_params'         => '',
            'android_id'            => $this->parent->storage->getUser()->getOpenudid(),
            'ad_personality_mode'   => '1',
            'ts'                    => time(),
            'js_sdk_version'        => '',
            'app_type'              => 'normal',
            'os_api'                => '22',
            'device_type'           => $this->parent->storage->getUser()->getDeviceType(),
            'ssmix'                 => 'a',
            'manifest_version_code' => '2019011531',
            'dpi'                   => '320',
            'carrier_region'        => 'US',
            'carrier_region_v2'     => '286',
            'app_name'              => 'musical_ly',
            'version_name'          => '9.9.0',
            'timezone_offset'       => '7200',
            'pass-route'            => '1',
            'pass-region'           => '1',
            'is_my_cn'              => 0,
            'fp'                    => '',
            'ac'                    => 'wifi',
            'update_version_code'   => '2019011531',
            'channel'               => 'googleplay',
            '_rticket'              => time(),
            'device_platform'       => 'android',
            'iid'                   => $this->parent->storage->getUser()->getInstallId(),
            'build_number'          => '9.9.0',
            'version_code'          => '990',
            'timezone_name'         => 'Europe/Istanbul',
            'account_region'        => 'V',
            'openudid'              => $this->parent->storage->getUser()->getOpenudid(),
            'device_id'             => $this->parent->storage->getUser()->getDeviceId(),
            'sys_region'            => 'US',
            'app_language'          => 'us',
            'resolution'            => $this->parent->storage->getUser()->getResolution(),
            'os_version'            => '5.1.1',
            'device_brand'          => strtolower($this->parent->storage->getUser()->getDeviceBrand()),
            'language'              => 'us',
            'aid'                   => '1233',
            'mcc_mnc'               => '28601',
            'as'                    => 'a135251de4387eb1892833',
            'cp'                    => '5880e05c499fd518e1OsWa',
            'mas'                   => md5(time())
        ];
        foreach ($params as $key => $value):
            $this->addParam($key,$value);
        endforeach;
    }

    public function disableTokens(bool $isDisabled) : Request
    {
        $this->isDisabledTokens = $isDisabled;
        return $this;
    }

    public function isDisabledTokens() : bool
    {
        return $this->isDisabledTokens;
    }

    public function setPostPayload($payload) : Request
    {
        $this->postPayload = $payload;
        return $this;
    }

    public function getPostPayload() : ?string
    {
        return $this->postPayload;
    }

    /**
     * @return HttpClient
     */
    public function execute(): HttpClient
    {
        return new HttpClient($this);
    }
}
