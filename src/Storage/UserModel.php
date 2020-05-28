<?php


namespace TikTokAPI\Storage;


use Exception;

class UserModel
{
    protected array $data;

    /**
     * UserModel constructor.
     * @param $jsonData
     * @throws Exception
     */
    public function __construct($jsonData)
    {
        $this->data = json_decode($jsonData, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @return mixed|null
     */
    public function getCookie()
    {
        return $this->data['cookie'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getUsername()
    {
        return $this->data['username'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function deviceUseragent()
    {
        return $this->data['useragent'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function deviceInstallID()
    {
        return $this->data['iid'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function deviceId()
    {
        return $this->data['device_id'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function deviceOpenUDID()
    {
        return $this->data['openudid'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function deviceBrand()
    {
        return $this->data['device_brand'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function deviceType()
    {
        return $this->data['device_type'] ?? null;
    }


}