<?php


namespace TikTokAPI\Http;

use Exception;
use JsonException;
use RuntimeException;
use TikTokAPI\Encryption\CreateToken;

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
        $curl = curl_init();
        $options = [
            CURLOPT_URL => $this->request->getBaseUrl().$this->request->getEndpoint().$this->request->getRequestParams(),
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_HEADER          => true,
            CURLOPT_SSL_VERIFYHOST  => false,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_ENCODING        => 'gzip, deflate'
        ];

        if ($this->request->parent->getProxy() !== null):
            $options[CURLOPT_PROXY] = $this->request->parent->getProxy();
        endif;

        if ($this->request->getNeedsCookie() === true):
            $options[CURLOPT_COOKIEFILE]  = $this->request->parent->storage->getCookiePath();
        $options[CURLOPT_COOKIEJAR]   = $this->request->parent->storage->getCookiePath();
        endif;

        if ($this->request->hasPost()):
            $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $this->request->getRequestPosts();
        $this->request->addHeader('X-SS-STUB', strtoupper(md5($this->request->getRequestPosts()))); elseif ($this->request->getPostPayload() !== null):
            $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $this->request->getPostPayload();
        endif;

        $this->request->addHeader('User-Agent', $this->request->parent->storage->getUser()->deviceUseragent());


        if ($this->request->isDisabledTokens() === false):
            $createToken = new CreateToken($this->request->getBaseUrl().$this->request->getEndpoint(), $this->request->getRequestParams(true), $this->request->getRequestPosts(), $this->request->getRequestHeaders(true));
        $this->request->addHeader('X-Gorgon', $createToken->getXGorgon());
        $this->request->addHeader('X-Khronos', $createToken->getXKhronos());
        endif;



        if ($this->request->hasHeaders()):
            $options[CURLOPT_HTTPHEADER] = $this->request->getRequestHeaders();
        endif;

        if ($this->request->hasCurlOptions()):
            foreach ($this->request->getRequestCurl() as $key => $value):
                $options[$key] = $value;
        endforeach;
        endif;
        curl_setopt_array($curl, $options);
        $resp = curl_exec($curl);
        $header_len = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($resp, 0, $header_len);
        $header = $this->getHeadersFromResponse($header);
        $resp = (substr($resp, $header_len));
        curl_close($curl);
        print_r($options);
        $this->requestResponse = $resp;
        $this->requestResponseHeaders = $header;
    }

    /**
     * @return array
     */
    public function getResponseHeaders(): array
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
    public function getResponse(): string
    {
        return $this->requestResponse;
    }


    /**
     * @param bool $assoc
     * @return mixed
     */
    public function getDecodedResponse($assoc = true)
    {
        if (!$this->requestResponse) {
            throw new RuntimeException('No Response From Server');
        }
        try {
            return json_decode($this->requestResponse, $assoc, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return "";
        }
    }

    /**
     * @param $response
     * @return array
     */
    protected function getHeadersFromResponse($response): array
    {
        $headers = [];

        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line) {
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                [$key, $value] = explode(': ', $line);

                $headers[$key] = $value;

                if (strtolower($key) === 'set-cookie') {
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
