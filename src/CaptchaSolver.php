<?php


namespace TikTokAPI;

use Exception;
use Imagick;
use ImagickException;
use JsonException;

class CaptchaSolver
{
    protected int $x;
    protected int $y;
    protected string $url1;
    protected string $url2;
    protected array $reply;
    protected string $id;
    protected string $captchaTMP;
    protected string $localUrl1;
    protected string $localUrl2;

    /**
     * @param $id
     * @param $url1
     * @param $url2
     * @param $y
     * @return false|string
     * @throws ImagickException
     * @throws JsonException
     */
    public function solve($id, $url1, $url2, $y)
    {
        $this->captchaTMP = __DIR__.'/../examples/captchas/';
        $this->id    = $id;
        $this->url1  = $url1;
        $this->url2  = $url2;
        $this->y     = (int)$y;
        $this->x     = 0;
        $this->reply = [];

        $this->localUrl1 = $this->captchaTMP.md5(microtime()).".jpg";
        $this->localUrl2 = $this->captchaTMP.md5(sha1(microtime())).".png";

        file_put_contents($this->localUrl1, file_get_contents($url1));
        file_put_contents($this->localUrl2, file_get_contents($url2));

        $canvas  = new Imagick($this->localUrl1);
        $piece   = new Imagick($this->localUrl2);

        $canvas->charcoalImage(5, 1);
        $piece->charcoalImage(5, 1);

        $canvas->subImageMatch($piece, $offset, $similarity);
        $this->x = $offset["x"];
        return $this->createReply();
    }


    /**
     * @throws JsonException|Exception
     */
    public function createReply()
    {
        $generateReply = [
            "id" => $this->id,
            "modified_img_width" => 268,
            "mode" => "slide",
        ];

        for ($i = 0; $i <=  $this->x; $i++) {
            $time = $i * 8 + random_int(10, 30);
            $generateReply["reply"][] = ["relative_time" => $time,"y" => $this->y,"x" => $i];
        }
        return json_encode($generateReply, JSON_THROW_ON_ERROR);
    }
}
