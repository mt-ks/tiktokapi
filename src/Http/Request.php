<?php

namespace TikTokAPI\Http;

use TikTokAPI\Constants;
use TikTokAPI\TikTok;

class Request
{
    protected TikTok $parent;
    protected string $endpoint;
    protected array $_post = [];
    protected array $_param = [];
    protected array $_header = [];
    protected array $_curl = [];
    protected bool  $disableDefaultParams = false;
    protected int $_base = 0;

    public function __construct($endpoint, TikTok $parent)
    {
        $this->endpoint = $endpoint;
        $this->parent = $parent;
    }


    public function setBaseUrl($i = 0): Request
    {
        $this->_base = $i;
        return $this;
    }

    protected function getBaseUrl(): string
    {
        return Constants::API_URL[$this->_base] ?? 'https://api2-19-h2.musical.ly/';
    }

    public function addPost($key, $value): Request
    {
        $this->_post[$key] = $value;
        return $this;
    }

    public function addParam($key, $value): Request
    {
        $this->_param[$key] = $value;
        return $this;
    }

    public function addHeader($key, $value): Request
    {
        $this->_header[$key] = $value;
        return $this;
    }

    public function addCurl($key, $value): Request
    {
        $this->_curl[$key] = $value;
        return $this;
    }

    /**
     * @param bool $status
     * @return Request
     */
    public function setDisableDefaultParams($status = false): Request
    {
        $this->disableDefaultParams = $status;
        return $this;
    }
    public function isDisabledDefaultParams() : bool
    {
        return $this->disableDefaultParams;
    }

    public function hasPost(): bool
    {
        return count($this->_post) > 0;
    }

    public function hasHeaders(): bool
    {
        return count($this->_header) > 0;
    }

    public function hasCurlOptions(): bool
    {
        return count($this->_curl) > 0;
    }

    public function hasParams(): bool
    {
        return count($this->_param) > 0;
    }

    public function getRequestPosts()
    {
        return http_build_query($this->_post);
    }

    public function getRequestParams($withQM = true)
    {
        return ($withQM) ? '?'.http_build_query($this->_param) : http_build_query($this->_param);
    }

    public function getRequestHeaders($asArray = false) : array
    {
        if ($asArray)
            return $this->_header;

        $headers = [];
        foreach ($this->_header as $key => $value):
            $headers[] = sprintf('%s: %s', $key, $value);
        endforeach;
        return $headers;
    }

    public function getRequestCurl() : array
    {
        return $this->_curl;
    }

    /**
     * @return HttpClient
     */
    protected function execute(): HttpClient
    {
        return (new HttpClient($this));
    }


}