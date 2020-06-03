<?php


namespace TikTokAPI\Storage;

use Exception;
use RuntimeException;
use TikTokAPI\Constants;
use TikTokAPI\Device\Device;
use TikTokAPI\Models\UserModel;

class UserStorage
{
    protected string $defaultPath    = '';
    protected string $settingsFolder = '';
    protected string $username;
    protected array $deviceInfo      = [];
    protected array $userConfig      = [];


    /**
     * UserStorage constructor.
     * @param $username
     * @param array $deviceInfo
     * @param array $config
     * @throws Exception
     */
    public function __construct($username, $deviceInfo = [], $config = [])
    {
        $this->username = $username;
        $this->deviceInfo = $deviceInfo;
        $this->initConfig($config);
        $this->checkUserFile();
        $this->userConfigToArray();
    }

    /**
     * @throws Exception
     */
    protected function checkUserFile() : void
    {
        if (!file_exists($this->getSessionPath())) {
            fopen($this->getSessionPath(), 'wb');
            fwrite(fopen($this->getSessionPath(), 'wb+'), json_encode($this->defaultSettingsContent(), JSON_THROW_ON_ERROR));
        }
    }

    protected function getFileContent()
    {
        return fopen($this->getSessionPath(), 'rb');
    }


    /**
     * @throws Exception
     */
    protected function userConfigToArray() : void
    {
        $getContent = $this->getFileContent();
        $decode     = json_decode(stream_get_contents($getContent), true, 512, JSON_THROW_ON_ERROR);
        foreach ($decode as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @param $key
     * @param $value
     * @return UserStorage
     * @throws Exception
     */
    public function set($key, $value) : self
    {
        $this->userConfig[$key] = $value;
        $this->save();
        return  $this;
    }

    /**
     * @throws Exception
     */
    protected function save(): void
    {
        fwrite(fopen($this->getSessionPath(), "wb+"), json_encode($this->userConfig, JSON_THROW_ON_ERROR));
    }


    public function get($key, $default) : ?string
    {
        return $this->userConfig[$key] ?? $default;
    }


    /**
     * @return UserModel
     */
    public function getUser(): UserModel
    {
        try{
            return new UserModel((array)json_decode(stream_get_contents($this->getFileContent()), true, 512, JSON_THROW_ON_ERROR));
        }catch (Exception $e)
        {
            return new UserModel([]);
        }
    }


    /**
     * @param $config
     */
    protected function initConfig($config) : void
    {
        if (isset($config['folder']) && !is_dir($config['folder'] . $this->username) && !mkdir($concurrentDirectory = $concurrentDirectory = $concurrentDirectory = $config['folder'].'/'. $this->username, 0777, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }

        $this->defaultPath = $config['folder'].'/'.$this->username;
    }


    public function getCookiePath() : string
    {
        return ($this->defaultPath !== '') ? rtrim($this->defaultPath, '/').'/'.$this->username.'-cookies.dat' : $this->username.'-cookies.dat';
    }

    protected function getSessionPath() : string
    {
        return ($this->defaultPath !== '') ? rtrim($this->defaultPath, '/').'/'.$this->username.'.json' : $this->username.'.json';
    }

    protected function defaultSettingsContent(): array
    {
        return [
            'username'     => $this->username,
            'useragent'    => $this->deviceInfo['useragent'] ?? '',
            'openudid'     => $this->deviceInfo['openudid'] ?? '',
            'device_id'    => $this->deviceInfo['device_id'] ?? '',
            'install_id'   => $this->deviceInfo['install_id'] ?? '',
            'device_type'  => $this->deviceInfo['device_type'] ?? '',
            'device_brand' => $this->deviceInfo['device_brand'] ?? '',
            'dpi'          => $this->deviceInfo['dpi'] ?? '',
            'resolution'   => $this->deviceInfo['resolution'] ?? Constants::RESOLUTION,
            'carrier_region_v2' => $this->deviceInfo['carrier_region_v2'] ?? 286,
            'carrier_region' => $this->deviceInfo['carrier_region'] ?? Constants::REGION,
            'mcc_mnc' => $this->deviceInfo['carrier_region'] ?? 28601
        ];
    }
}
