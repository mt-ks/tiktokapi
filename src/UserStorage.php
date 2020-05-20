<?php


namespace TikTokAPI;


class UserStorage
{
    protected string $defaultPath = '';
    public function __construct($deviceInfo = [], $config = [])
    {

    }

    /**
     * @return string[]
     */
    protected function defaultConfig() : array
    {
        return [
          'file_storage' => '',
        ];
    }

    protected function hasSettingsFile() : bool
    {

    }

    protected function hasCookieFile() : bool {

    }
}