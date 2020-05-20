<?php


namespace TikTokAPI;


use JsonException;
use MClient\Request;

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
            ->getDecodedResponse(true);
    }

    public function setProxy($proxy) : self
    {
        $this->_proxy = $proxy;
        return $this;
    }


    protected function setBase($base = 0) : string
    {
        $this->_base = $base;
        return $this;
    }

    /**
     * @param bool $bool
     * @return TikTok
     */
    protected function disableParams($bool) : self
    {
        $this->_disableParams = $bool;
        return $this;
    }

    /**
     * @return array
     */
    public function defaultParams() : array
    {
        return [];
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