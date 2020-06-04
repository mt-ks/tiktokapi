<?php


namespace TikTokAPI\Models;


use EJM\MainMapper;

/**
 * @method getNow()
 * @method getLogid()
 */

class Extra extends MainMapper {
    const MAP =
        [
            'now' => 'string',
            'logid' => 'string',
        ];
}
