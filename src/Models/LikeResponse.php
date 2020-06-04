<?php


namespace TikTokAPI\Models;



use EJM\MainMapper;


/**
 * @method getIsDigg()
 * @method Extra getExtra()
 * @method LogPb getLogPb()
 * @method getStatusCode()
 */

class LikeResponse extends MainMapper{
    const MAP =
        [
            'is_digg' => 'string',
            'extra' => Extra::class,
            'log_pb' => LogPb::class,
            'status_code' => 'string',
        ];
}

