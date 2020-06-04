<?php


namespace TikTokAPI\Models;



use EJM\MainMapper;

/**
 * @method int getStatusCode()
 * @method int getFollowStatus()
 * @method int getWatchStatus()
 * @method Extra getExtra()
 * @method LogPb getLogPb()
 */

class FollowResponse extends MainMapper {
    const MAP =
        [
            'status_code' => 'int',
            'follow_status' => 'int',
            'watch_status' => 'int',
            'extra' => Extra::class,
            'log_pb' => LogPb::class,
        ];
}