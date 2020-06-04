<?php


namespace TikTokAPI\Models\Captcha;

use EJM\MainMapper;

/**
 * @method getRet()
 * @method getAction()
 * @method getMsgType()
 * @method getMsg()
 */

class SolveResponse extends MainMapper{
    const MAP =
        [
            'ret' => 'string',
            'action' => 'string',
            'msg_type' => 'string',
            'msg' => 'string',
        ];
}

