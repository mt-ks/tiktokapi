<?php

namespace TikTokAPI;


use Exception;
use TikTokAPI\Encryption\Encryption;
use TikTokAPI\Http\Request;
use TikTokAPI\Models\RegisterDeviceModel;
use TikTokAPI\Storage\UserStorage;
use TikTokAPI\CaptchaSolver;

class TikTok
{
    protected string $username;
    protected string $password;
    protected array $deviceInfo;
    protected array $config;
    protected ?string $proxy = null;
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
        $this->checkDeviceStatus();
    }

    /**
     * @throws Exception
     */
    public function checkDeviceStatus()
    {
        if (!$this->storage->getUser()->deviceInstallID())
        {
            $this->changeDeviceInfo();
        }
    }

    /**
     * @throws Exception
     */
    public function changeDeviceInfo(): void
    {
        $registerNewDevice = $this->registerDevice();
        $this->storage->set('device_type',$registerNewDevice->getDeviceType())
            ->set('device_brand',$registerNewDevice->getDeviceBrand())
            ->set('openudid',$registerNewDevice->getOpenUDID())
            ->set('device_id',$registerNewDevice->getDeviceId())
            ->set('useragent',$registerNewDevice->getUseragent())
            ->set('iid',$registerNewDevice->getInstallId());
    }

    public function setProxy(string $proxy) : TikTok
    {
        $this->proxy = $proxy;
        return $this;
    }

    public function getProxy() : ?string {
        return $this->proxy;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function login()
    {
        return $this->request('passport/user/login/')
            ->addPost('username',Encryption::xorEncrypt($this->username))
            ->addPost('password',Encryption::xorEncrypt($this->password))
            ->addPost('mix_mode','')
            ->addPost('mobile','')
            ->addPost('captcha','')
            ->addPost('mix_mode',1)
            ->addPost('multi_login',1)
            ->execute()
            ->getDecodedResponse(true);
    }



    /**
     * @param $secUserId
     * @param int $channelId
     * @return mixed
     * @throws Exception
     */
    public function follow(
        $secUserId,
        $channelId = 3)
    {
         return $this->request('aweme/v1/commit/follow/user/')
             ->setBaseUrl(3)
             ->addParam('sec_user_id', $secUserId)
             ->addParam('from', 0)
             ->addParam('from_pre', -1)
             ->addParam('type', 1)
             ->addParam('channel_id', $channelId)
             ->execute()
             ->getDecodedResponse();
    }

    public function like(
        $mediaId)
    {
        return $this->request('aweme/v1/commit/item/digg/')
            ->setBaseUrl(3)
            ->addParam('aweme_id', $mediaId)
            ->addParam('type', 1)
            ->execute()
            ->getDecodedResponse();

    }

    public function registerVerifyAge()
    {
        return $this->request('aweme/v1/register/verification/age/')
            ->addParam('birthday','1998-05-30')
            ->execute()
            ->getDecodedResponse();
    }

    public function register($email, $password)
    {
        $request = $this->request('passport/email/register/v2/')
            ->addPost('password', Encryption::xorEncrypt($password))
            ->addPost('account_sdk_source', 'app')
            ->addPost('mix_mode', 1)
            ->addPost('email', Encryption::xorEncrypt($email))
            ->execute();

        return $request->getDecodedResponse();
    }

    public function checkEmailRegistered($email)
    {
        return $this->request('passport/user/check_email_registered/')
            ->addPost('mix_mode',1)
            ->addPost('email',Encryption::xorEncrypt($email))
            ->execute()
            ->getDecodedResponse();
    }

    /**
     * @param $newUsername
     * @return mixed
     * @throws Exception
     */
    public function changeUsername($newUsername)
    {
        return $this->request('aweme/v1/commit/user/')
            ->addPost('unique_id',$newUsername)
            ->execute()
            ->getDecodedResponse();
    }


    public function getPuzzleAddress(): string
    {
        $iid = $this->storage->getUser()->deviceInstallID();
        $device_id = $this->storage->getUser()->deviceId();
        return "https://verification-va.musical.ly/view?aid=1233&lang=tr&app_name=musical_ly&iid={$iid}&vc=2021409030&did={$device_id}&ch=googleplay&os=0&challenge_code=1105";
    }

    public function getCaptcha($node = 1105)
    {
        return $this->request('get')
            ->setNeedsCookie(false)
            ->setBaseUrl(1)
            ->addParam('aid', 1233)
            ->addParam('lang', Constants::LANGUAGE)
            ->addParam('app_name', Constants::APP_NAME)
            ->addParam('iid', $this->storage->getUser()->deviceInstallID())
            ->addParam('vc', Constants::VERSION_CODE)
            ->addParam('did', $this->storage->getUser()->deviceId())
            ->addParam('ch', Constants::CHANNEL)
            ->addParam('challenge_code', $node)
            ->addParam('os', 0)
            ->setDisableDefaultParams(true)
            ->disableTokens(true)
            ->addHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-J200F Build/LMY47X; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/80.0.3987.117 Mobile Safari/537.36')
            ->execute()
            ->getDecodedResponse();
    }


    public function solveCaptcha($id, $url1, $url2, $tip_y)
    {
        $solver = (new CaptchaSolver())->solve($id, $url1, $url2, $tip_y);
        return $this->request('verify')
            ->setNeedsCookie(false)
            ->setBaseUrl(1)
            ->addParam('aid', 1233)
            ->addParam('lang', Constants::LANGUAGE)
            ->addParam('app_name', Constants::APP_NAME)
            ->addParam('iid', $this->storage->getUser()->deviceInstallID())
            ->addParam('vc', Constants::VERSION_CODE)
            ->addParam('did', $this->storage->getUser()->deviceId())
            ->addParam('ch', Constants::CHANNEL)
            ->addParam('challenge_code', 1105)
            ->addParam('os', 0)
            ->disableTokens(true)
            ->setPostPayload($solver)
            ->execute()
            ->getDecodedResponse();
    }


    /**
     * @return mixed
     * @throws Exception
     */
    public function registerDevice()
    {
        $binary = Encryption::deviceRegisterData();
        $register = $this->request('service/2/device_register/')
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

        return new RegisterDeviceModel($register,$binary);
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