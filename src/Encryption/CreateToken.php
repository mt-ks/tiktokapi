<?php


namespace TikTokAPI\Encryption;


use Exception;
use MClient\Request;

class CreateToken
{
    private $request;
    private const SERVER_ADDRESS = 'http://192.168.0.40:4000/?tiktok_access_token=1212554612922_111213141516997755mbyMBY';


    /**
     * CreateToken constructor.
     * @param $url
     * @param $query
     * @param $postData
     * @param $headers
     * @throws Exception
     */
    public function __construct($url, $query, $postData, $headers = [])
    {
        $data = [
            'url' => $url,
            'query' => $query,
            'body' => $postData,
            'headers' => $headers
        ];
        $c = curl_init();
        $o = [
            CURLOPT_URL => self::SERVER_ADDRESS,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POSTFIELDS => json_encode($data, JSON_THROW_ON_ERROR),
            CURLOPT_HTTPHEADER => [
                'content-type: application/json'
            ]
        ];
        curl_setopt_array($c, $o);
        $r = curl_exec($c);
        curl_close($c);
        $this->request = json_decode($r, true, 512, JSON_THROW_ON_ERROR);

    }

    /**
     * @return mixed|null
     */
    public function getXKhronos()
    {
        return $this->request['X-Khronos'] ?? null;
    }

    /**
     * @return mixed|null
     */
    public function getXGorgon()
    {
        return $this->request['X-Gorgon'] ?? null;
    }

}