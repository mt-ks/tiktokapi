<?php


namespace TikTokAPI\Models\Captcha;


use EJM\MainMapper;

/**
 * @method getUrl1()
 * @method getUrl2()
 * @method getTipY()
 */

class Question extends MainMapper {
    const MAP =
        [
            'url1' => 'string',
            'url2' => 'string',
            'tip_y' => 'string',
        ];
}

