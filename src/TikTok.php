<?php

namespace TikTokAPI;


use Exception;
use TikTokAPI\Encryption\Encryption;
use TikTokAPI\Http\Request;
use TikTokAPI\Storage\UserStorage;

class TikTok
{
    protected string $username;
    protected string $password;
    protected array $deviceInfo;
    protected array $config;
    public UserStorage $storage;

    /**
     * TikTok constructor.
     * @param $username
     * @param $password
     * @param array $deviceInfo
     * @param array $config
     * @throws Exception
     */
    public function __construct($username,$password,$deviceInfo = [],$config = ['folder' => __DIR__.'/sessions/'])
    {
        $this->username = $username;
        $this->password = $password;
        $this->storage = new UserStorage($this->username,$deviceInfo,$config);
    }

    public function login()
    {
        return $this->request('passport/user/login/')
            ->addPost('username',Encryption::xorEncrypt($this->username))
            ->addPost('password',Encryption::xorEncrypt($this->password))
            ->addPost('account_sdk_source','app')
            ->addPost('mix_mode',1)
            ->addPost('multi_login',1)
            ->execute()
            ->getResponse();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function registerDevice()
    {
        $binary = Encryption::deviceRegisterData();
        return $this->request('service/2/device_register/')
            ->setBaseUrl(2)
            ->addParam('ac','wifi')
            ->addParam('channel','googleplay')
            ->addParam('aid','1233')
            ->addParam('app_name','musical_ly')
            ->addParam('version_code','130211')
            ->addParam('version_name','13.2.11')
            ->addParam('device_platform','android')
            ->addParam('ab_version','13.2.11')
            ->addParam('ssmix','a')
            ->addParam('device_type',$binary['data']['header']['device_model'])
            ->addParam('device_brand',$binary['data']['header']['device_brand'])
            ->addParam('language','en')
            ->addParam('os_api',25)
            ->addParam('os_version','7.1.2')
            ->addParam('uuid',Encryption::generateUUID(false))
            ->addParam('openudid',$binary['data']['header']['openudid'])
            ->addParam('manifest_version_code','2019092901')
            ->addParam('resolution',$binary['data']['header']['resolution'])
            ->addParam('dpi',$binary['data']['header']['density_dpi'])
            ->addParam('update_version_code','2019092901')
            ->addParam('app_type','normal')
            ->addParam('sys_region','US')
            ->addParam('is_my_cn',0)
            ->addParam('pass-route',1)
            ->addParam('mcc_mnc',$binary['data']['header']['mcc_mnc'])
            ->addParam('pass-region','1')
            ->addParam('timezone_name','America/New_York')
            ->addParam('carrier_region_v2',$binary['carrier'][0])
            ->addParam('timezone_offset',0)
            ->addParam('build_number','13.2.11')
            ->addParam('region','US')
            ->addParam('uoo',0)
            ->addParam('app_language','en')
            ->addParam('carrier_region','US')
            ->addParam('locale','en')
            ->addParam('ac2','wifi5g')
            ->addParam('_rticket', time() * 1000)
            ->addParam('ts',time())
            ->setDisableDefaultParams(true)
            ->addCurl(CURLOPT_POST,true)
            ->addCurl(CURLOPT_POSTFIELDS,$binary['encoded'])
            ->addCurl(CURLOPT_HTTPHEADER,[
                'Content-Type: application/json; charset=utf-8',
                'host: applog.musical.ly',
                'sdk-version: 1',
                'accept: application/json',
                'user-agent: '.$binary['ua']
            ])
            ->execute()
            ->getResponse();
    }

    /**
     * @param $endpoint
     * @return Request
     */
    public function request($endpoint)
    {
        return new Request($endpoint,$this);
    }
}