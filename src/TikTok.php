<?php

namespace TikTokAPI;


use Exception;
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

    /**
     * @param $endpoint
     * @return Request
     */
    public function request($endpoint)
    {
        return new Request($endpoint,$this);
    }
}