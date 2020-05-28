<?php


namespace TikTokAPI\Storage;


use Exception;
use RuntimeException;
use TikTokAPI\Device\Device;

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
    public function __construct($username,$deviceInfo = [],$config = [])
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
        if (!file_exists($this->getSessionPath())){
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
        foreach ($decode as $key => $value)
        {
            $this->set($key,$value);
        }
    }

    /**
     * @param $key
     * @param $value
     * @return UserStorage
     * @throws Exception
     */
    public function set($key,$value) : self
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
        fwrite(fopen($this->getSessionPath(),"wb+"), json_encode($this->userConfig, JSON_THROW_ON_ERROR));
    }


    public function get($key,$default) : ?string
    {
        return $this->userConfig[$key] ?? $default;
    }

    /**
     * @return UserModel
     * @throws Exception
     */
    public function getUser(): UserModel
    {
        return new UserModel(stream_get_contents($this->getFileContent()));
    }


    /**
     * @param $config
     */
    protected function initConfig($config) : void
    {

        if (isset($config['folder']) && !is_dir($config['folder']) && !mkdir($concurrentDirectory = $concurrentDirectory = $config['folder'], 0777, true) && !is_dir($concurrentDirectory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }
        if (isset($config['folder'])){ $this->defaultPath = $config['folder']; }
    }

    protected function getSessionPath() : string
    {
        return ($this->defaultPath !== '') ? rtrim($this->defaultPath,'/').'/'.$this->username.'.json' : $this->username.'.json';
    }

    protected function defaultSettingsContent(): array
    {
        return [
            'username'     => $this->username,
            'cookie'       => '',
            'useragent'    => $this->deviceInfo['useragent'] ?? '',
            'openudid'     => $this->deviceInfo['openudid'] ?? '',
            'device_id'    => $this->deviceInfo['device_id'] ?? '',
            'iid'          => $this->deviceInfo['iid'] ?? '',
            'device_type'  => $this->deviceInfo['device_type'] ?? '',
            'device_brand' => $this->deviceInfo['device_brand'] ?? '',
        ];
    }

}