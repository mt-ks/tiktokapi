<?php

namespace TikTokAPI;


use JsonException;
use MClient\Request;
use TikTokAPI\Device\UserAgentBuilder;

class TikTok
{

    protected string $username;
    protected string $password;
    public UserStorage $storage;
    protected int $_base = 0;
    protected ?string $_proxy = null;
    protected bool $_disableParams = false;

    /**
     * TikTok constructor.
     * @param $username
     * @param $password
     * @param array $deviceInfo
     * @param array $config  User Storage config.
     */
    public function __construct($username,$password,$deviceInfo = [],$config = [])
    {
        $this->username = $username;
        $this->password = $password;
        $this->storage  = new UserStorage($deviceInfo,$config);
    }

    /**
     * @return mixed
     * @throws JsonException
     */
    public function login()
    {
        return $this->request('passport/user/login/')
            ->addPost('password',Encryption::xorEncrypt($this->password))
            ->addPost('username',Encryption::xorEncrypt($this->username))
            ->addPost('account_sdk_source', 'app')
            ->addPost('mix_mode', 1)
            ->addPost('multi_login', 1)
            ->addPost('ts',time())
            ->execute()
            ->getResponse();
    }

    /**
     * @param $proxy
     * @return $this
     */
    public function setProxy($proxy) : self
    {
        $this->_proxy = $proxy;
        return $this;
    }


    /**
     * @param int $base
     * @return $this|string
     */
    protected function setBase($base = 0) : string
    {
        $this->_base = $base;
        return $this;
    }

    /**
     * @return array
     */
    public function defaultParams() : array
    {
        return [
            'filter_warn'           => 0,
            'bid_ad_params'         => '',
            'android_id'            => '',
            'ad_personality_mode'   => '1',
            'ts'                    => time(),
            'js_sdk_version'        => '',
            'app_type'              => 'normal',
            'os_api'                => '22',
            'device_type'           => '',
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
            'iid'                   => '',
            'build_number'          => '9.9.0',
            'version_code'          => '990',
            'timezone_name'         => 'Europe/Istanbul',
            'account_region'        => 'V',
            'openudid'              => '',
            'device_id'             => '',
            'sys_region'            => 'US',
            'app_language'          => 'us',
            'resolution'            => '720*1280',
            'os_version'            => '5.1.1',
            'device_brand'          => '',
            'language'              => 'us',
            'aid'                   => '1233',
            'mcc_mnc'               => '28601',
            'as'                    => substr(md5(time()),10),
            'cp'                    => substr(md5(time()),10),
            'mas'                   => md5(sha1(time()))
        ];
    }


    protected function getBase() : ?string
    {
        return Constants::API_URL[$this->_base] ?? null;
    }

    /**
     * @param string $endpoint
     * @param bool $disableParams
     * @return Request
     */
    public function request($endpoint = '',$disableParams = false) : Request
    {
        $request = new Request($this->getBase().$endpoint);
        $request->addCurlOptions(CURLOPT_USERAGENT,UserAgentBuilder::build());
        if ($this->_proxy):
            $request->setProxy($this->_proxy);
        endif;
        if ($disableParams !== true):
            foreach ($this->defaultParams() as $key => $value)
            {
                $request->addParam($key,$value);
            }
        endif;
        return $request;
    }
}