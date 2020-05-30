<?php

namespace TikTokAPI\Models;

use Exception;

class RegisterDeviceModel
{
    protected array $data;
    protected array $registerData = [];

    /**
     * RegisterDeviceModel constructor.
     * @param $responseData
     * @param $registerData
     * @throws Exception
     */
    public function __construct($responseData, $registerData)
    {
        $this->data = json_decode($responseData, true, 512, JSON_THROW_ON_ERROR);
        $this->registerData = $registerData;
    }

    public function getInstallId()
    {
        return $this->data['install_id_str'] ?? null;
    }

    public function getDeviceId()
    {
        return $this->data['device_id_str'] ?? null;
    }

    public function getOpenUDID() : ?string
    {
        return $this->registerData['data']['header']['openudid'] ?? null;
    }

    public function getServerTime()
    {
        return $this->data['server_time'] ?? null;
    }

    public function getUseragent()
    {
        return $this->registerData['ua'] ?? null;
    }

    public function getDeviceType()
    {
        return $this->registerData['data']['header']['device_model'] ?? null;
    }

    public function getDeviceBrand()
    {
        return $this->registerData['data']['header']['device_brand'] ?? null;
    }

    /**
     * @return false|string
     * @throws Exception
     */
    public function asJson()
    {
        return json_encode([
            'register' => $this->data,
            'data'     => $this->registerData
        ], JSON_THROW_ON_ERROR);
    }
}
