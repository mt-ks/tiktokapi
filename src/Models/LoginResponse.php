<?php


namespace TikTokAPI\Models;


use EJM\MainMapper;
use TikTokAPI\Models\LoginResponseModel;

/**
 * @method LoginResponseModel getData()
 * @method getMessage()
 */
class LoginResponse extends MainMapper
{
    const MAP =
        [
            'data' => LoginResponseModel::class,
            'message' => 'string',
        ];
}
