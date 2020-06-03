<?php

namespace TikTokAPI;

use Exception;
use ImagickException;
use JsonException;
use TikTokAPI\Encryption\Encryption;
use TikTokAPI\Http\Request;
use TikTokAPI\Models\RegisterDeviceModel;
use TikTokAPI\Models\RegisterDeviceResponse;
use TikTokAPI\Storage\UserStorage;

class TikTok
{
    /**
     * @var string
     */
    protected string $username;
    /**
     * @var string
     */
    protected string $password;
    /**
     * @var array
     */
    protected array $deviceInfo;
    /**
     * @var array
     */
    protected array $config;
    /**
     * @var string|null
     */
    protected ?string $proxy = null;
    /**
     * @var UserStorage
     */
    public UserStorage $storage;

    /**
     * TikTok constructor.
     * @param $username
     * @param $password
     * @param array $deviceInfo
     * @param array $config
     * @throws Exception
     */
    public function __construct($username, $password, $deviceInfo = [], $config = ['folder' => __DIR__.'/sessions/'])
    {
        $this->username = $username;
        $this->password = $password;
        $this->storage = new UserStorage($this->username, $deviceInfo, $config);
        $this->checkDeviceStatus();
    }

    /**
     * @throws Exception
     */
    public function checkDeviceStatus(): void
    {
        if (!$this->storage->getUser()->getInstallId()) {
            $this->changeDeviceInfo();
        }
    }

    /**
     * @throws Exception
     */
    public function changeDeviceInfo(): void
    {
        $registerNewDevice = $this->registerDevice();
        $this->storage->set('device_type', $registerNewDevice->getDeviceType())
            ->set('device_brand', $registerNewDevice->getDeviceBrand())
            ->set('openudid', $registerNewDevice->getOpenudid())
            ->set('device_id', $registerNewDevice->getDeviceIdStr())
            ->set('useragent', $registerNewDevice->getUseragent())
            ->set('install_id', $registerNewDevice->getInstallIdStr())
            ->set('carrier_region',$registerNewDevice->getCarrierRegion())
            ->set('carrier_region_v2',$registerNewDevice->getCarrierRegionV2())
            ->set('dpi',$registerNewDevice->getDpi())
            ->set('resolution',$registerNewDevice->getResolution())
            ->set('mcc_mnc',$registerNewDevice->getMccMnc());
    }

    /**
     * @param string $proxy
     * @return $this
     */
    public function setProxy(string $proxy) : TikTok
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProxy() : ?string
    {
        return $this->proxy;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function login()
    {
        return $this->request('passport/user/login/')
            ->addPost('mix_mode', 1)
            ->addPost('username', Encryption::xorEncrypt($this->username))
            ->addPost('email','')
            ->addPost('mobile', '')
            ->addPost('account','')
            ->addPost('password', Encryption::xorEncrypt($this->password))
            ->addPost('multi_login', 0)
            ->addPost('captcha', '')
            ->execute()
            ->getDecodedResponse(true);
    }



    /**
     * @param $secUserId
     * @param int $channelId
     * @return mixed
     * @throws Exception
     */
    public function follow($secUserId, $channelId = 3)
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

    /**
     * @param $mediaId
     * @return mixed|string
     */
    public function like($mediaId)
    {
        return $this->request('aweme/v1/commit/item/digg/')
            ->addParam('aweme_id', $mediaId)
            ->addParam('type', 1)
            ->execute()
            ->getDecodedResponse();
    }

    public function report($action,$verifyTime)
    {
        $log = [
            'aid' => 1233,
            'lang' => 'tr',
            'app_name' => 'musical_ly',
            'iid' => $this->storage->getUser()->getInstallId(),
            'vc' => Constants::VERSION_CODE,
            'did' => $this->storage->getUser()->getDeviceId(),
            'ch' => Constants::CHANNEL,
            'os' => '0',
            'challenge_code' => 1105,
            'time' => time(),
            'verify_time' => $verifyTime,
            'action' => $action,
            'detailinfos' => [
                'msg' => '滑动开始：可滑动'
            ],
            'mode' => 'slide'
        ];
        return $this->request('report')
            ->setBaseUrl(1)
            ->setPostPayload(json_encode($log, JSON_THROW_ON_ERROR))
            ->addHeader('Cookie','store-idc=maliva; store-country-code=tr; sec_sessionid=')
            ->addHeader('Referer',$this->getPuzzleAddress())
            ->setDisableDefaultParams(true)
            ->disableTokens(true)
            ->execute()
            ->getResponse();

    }

    /**
     * @return mixed|string
     */
    public function registerVerifyAge()
    {
        return $this->request('aweme/v1/register/verification/age/')
            ->addParam('birthday', '1998-05-30')
            ->execute()
            ->getDecodedResponse();
    }


    /**
     * @param $email
     * @param $password
     * @return mixed|string
     */
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

    /**
     * @param $email
     * @return mixed|string
     */
    public function checkEmailRegistered($email)
    {
        return $this->request('passport/user/check_email_registered/')
            ->addPost('mix_mode', 1)
            ->addPost('email', Encryption::xorEncrypt($email))
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
            ->addPost('unique_id', $newUsername)
            ->execute()
            ->getDecodedResponse();
    }


    /**
     * @return string
     * @throws Exception
     */
    public function getPuzzleAddress(): string
    {
        $iid = $this->storage->getUser()->getInstallId();
        $device_id = $this->storage->getUser()->getDeviceId();
        return "https://verification-va.musical.ly/view?aid=1233&lang=tr&app_name=musical_ly&iid={$iid}&vc=".Constants::VERSION_CODE."&did={$device_id}&ch=googleplay&os=0&challenge_code=1105";
    }

    /**
     * @param int $node
     * @return mixed|string
     * @throws Exception
     */
    public function getCaptcha($node = 1105)
    {
        return $this->request('get')
            ->setBaseUrl(1)
            ->setNeedsCookie(false)
            ->addParam('aid', 1233)
            ->addParam('lang', Constants::LANGUAGE)
            ->addParam('app_name', Constants::APP_NAME)
            ->addParam('iid', $this->storage->getUser()->getInstallId())
            ->addParam('vc', Constants::VERSION_CODE)
            ->addParam('did', $this->storage->getUser()->getDeviceId())
            ->addParam('ch', Constants::CHANNEL)
            ->addParam('challenge_code', $node)
            ->addParam('os', 0)
            ->setDisableDefaultParams(true)
            ->disableTokens(true)
            ->addHeader('User-Agent', 'Mozilla/5.0 (Linux; Android 5.1.1; SM-J200F Build/LMY47X; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/80.0.3987.117 Mobile Safari/537.36')
            ->execute()
            ->getDecodedResponse();
    }


    /**
     * @param $id
     * @param $url1
     * @param $url2
     * @param $tip_y
     * @return mixed|string
     * @throws ImagickException
     * @throws JsonException
     */
    public function solveCaptcha($id, $url1, $url2, $tip_y)
    {
        $solver = (new CaptchaSolver())->solve($id, $url1, $url2, $tip_y);
        return $this->request('verify')
            ->setBaseUrl(1)
            ->setNeedsCookie(false)
            ->addParam('aid', 1233)
            ->addParam('lang', Constants::LANGUAGE)
            ->addParam('app_name', Constants::APP_NAME)
            ->addParam('iid', $this->storage->getUser()->getInstallId())
            ->addParam('vc', Constants::VERSION_CODE)
            ->addParam('did', $this->storage->getUser()->getDeviceId())
            ->addParam('ch', Constants::CHANNEL)
            ->addParam('challenge_code', 1105)
            ->addParam('os', 0)
            ->addHeader('Referer',$this->getPuzzleAddress())
            ->disableTokens(true)
            ->setPostPayload($solver)
            ->execute()
            ->getDecodedResponse();
    }


    /**
     * @param bool $needCookie
     * @return mixed
     * @throws JsonException
     */
    public function registerDevice($needCookie = false)
    {
        $binary = Encryption::deviceRegisterData();
        $register = $this->request('service/2/device_register/')
            ->setBaseUrl(2)
            ->setNeedsCookie($needCookie)
            ->addParam('ac', 'wifi')
            ->addParam('channel', 'googleplay')
            ->addParam('aid', '1233')
            ->addParam('app_name', 'musical_ly')
            ->addParam('version_code', '130211')
            ->addParam('version_name', '13.2.11')
            ->addParam('device_platform', 'android')
            ->addParam('ab_version', '13.2.11')
            ->addParam('ssmix', 'a')
            ->addParam('device_type', $binary['data']['header']['device_model'])
            ->addParam('device_brand', $binary['data']['header']['device_brand'])
            ->addParam('language', 'tr')
            ->addParam('os_api', 29)
            ->addParam('os_version', 10)
            ->addParam('uuid', Encryption::generateRandomString(15))
            ->addParam('openudid', $binary['data']['header']['openudid'])
            ->addParam('manifest_version_code', '2019092901')
            ->addParam('resolution', $binary['data']['header']['resolution'])
            ->addParam('dpi', $binary['data']['header']['density_dpi'])
            ->addParam('update_version_code', '2019092901')
            ->addParam('app_type', 'normal')
            ->addParam('sys_region', 'tr')
            ->addParam('is_my_cn', 0)
            ->addParam('pass-route', 1)
            ->addParam('mcc_mnc', $binary['data']['header']['mcc_mnc'])
            ->addParam('pass-region', '1')
            ->addParam('timezone_name', 'America/New_York')
            ->addParam('carrier_region_v2', $binary['carrier'][0])
            ->addParam('timezone_offset', 10800)
            ->addParam('build_number', '13.2.11')
            ->addParam('region', 'TR')
            ->addParam('uoo', 0)
            ->addParam('app_language', 'tr')
            ->addParam('carrier_region', 'TR')
            ->addParam('locale', 'tr')
            ->addParam('ac2', 'wifi5g')
            ->addParam('_rticket', time() * 1000)
            ->addParam('ts', time())
            ->setDisableDefaultParams(true)
            ->addCurl(CURLOPT_POST, true)
            ->addCurl(CURLOPT_POSTFIELDS, $binary['encoded'])
            ->addCurl(CURLOPT_HTTPHEADER, [
                'Content-Type: application/json; charset=utf-8',
                'host: applog.musical.ly',
                'sdk-version: 1',
                'accept: application/json',
                'user-agent: '.$binary['ua']
            ])
            ->execute()
            ->getDecodedResponse();


        $register['useragent']   = $binary['ua'];
        $register['openudid']     = $binary['data']['header']['openudid'];
        $register['device_type']  = $binary['data']['header']['device_model'];
        $register['device_brand'] = $binary['data']['header']['device_brand'];
        $register['carrier_region'] =  Constants::REGION;
        $register['carrier_region_v2'] = $binary['carrier'][0];
        $register['resolution'] =  $binary['data']['header']['resolution'];
        $register['dpi'] =   $binary['data']['header']['density_dpi'];
        $register['mcc_mnc'] =   $binary['data']['header']['mcc_mnc'];

        print_r($register);

        return new RegisterDeviceResponse($register);

    }

    /**
     * @param $endpoint
     * @return Request
     */
    public function request($endpoint): Request
    {
        return new Request($endpoint, $this);
    }
}
