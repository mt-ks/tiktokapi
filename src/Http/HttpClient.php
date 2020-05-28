<?php


namespace TikTokAPI\Http;


use JsonException;
use RuntimeException;

class HttpClient
{
    protected Request $request;

    protected array $_cookies = [];
    /**
     * Response
     * @var string
     */
    protected ?string $requestResponse = null;

    /**
     * Response Headers
     * @var array
     */
    protected array $requestResponseHeaders = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function getResponseHeaders() : array
    {
        return $this->requestResponseHeaders;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getHeaderLine($key)
    {
        if (is_array($this->requestResponseHeaders) && isset($this->requestResponseHeaders[$key])) {
            return $this->requestResponseHeaders[$key];
        }
        return null;
    }

    /**
     * @return string
     */
    public function getResponse() : string
    {
        return $this->requestResponse;
    }


    /**
     * @param bool $assoc
     * @return mixed
     * @throws JsonException
     */
    public function getDecodedResponse($assoc = true)
    {
        if (!$this->requestResponse)
        {
            throw new RuntimeException('No Response From Server');
        }
        return json_decode($this->requestResponse, $assoc, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param $response
     * @return array
     */
    protected function getHeadersFromResponse($response) : array
    {
        $headers = [];

        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                [$key, $value] = explode(': ', $line);

                $headers[$key] = $value;

                if (strtolower($key) === 'set-cookie')
                {
                    $this->_cookies[] = $value;
                }

            }
        }

        return $headers;
    }

    /**
     * @param string $key
     * @return array|mixed|null
     */
    public function getCookies($key = '')
    {
        return (new HttpCookies($this->_cookies))->getCookie($key);
    }

}