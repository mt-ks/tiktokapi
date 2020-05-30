<?php


namespace TikTokAPI\Http;

class HttpCookies
{
    protected array $_cookies = [];

    /**
     * HttpCookies constructor.
     * @param array $cookieArray
     */
    public function __construct($cookieArray = [])
    {
        foreach ($cookieArray as $cookie) {
            $parseValues = explode(';', $cookie);
            if (isset($parseValues[0])) {
                $keyValue = explode('=', $parseValues[0]);
                if (isset($keyValue[0], $keyValue[1])) {
                    $this->_cookies[trim($keyValue[0])] = trim($keyValue[1]);
                }
            }
        }
    }

    /**
     * @param string $key
     * @return mixed|string|null
     */
    public function getCookie($key = '')
    {
        if ($key) {
            return $this->_cookies[$key] ?? null;
        }
        return $this->cookieStringBuilder();
    }

    /**
     * @return string
     */
    protected function cookieStringBuilder() : string
    {
        $cookieString = '';
        foreach ($this->_cookies as $key => $value) {
            $cookieString .= $key.'='.$value.'; ';
        }
        return $cookieString;
    }
}
