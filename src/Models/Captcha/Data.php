<?php


namespace TikTokAPI\Models\Captcha;


use EJM\MainMapper;

/**
 * @method getId()
 * @method getMode()
 * @method Question getQuestion()
 */

class Data extends MainMapper {
    const MAP =
        [
            'id' => 'string',
            'mode' => 'string',
            'question' => Question::class,
        ];
}