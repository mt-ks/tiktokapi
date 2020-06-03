<?php


namespace TikTokAPI\Models;

use EJM\MainMapper;
use Exception;

/**
 * Class UserModel
 * @package TikTokAPI\Storage
 * @method getUsername()
 * @method getDeviceId()
 * @method getUseragent()
 * @method getInstallId()
 * @method getOpenudid()
 * @method getDeviceBrand()
 * @method getDeviceType()
 * @method getResolution()
 * @method getDpi()
 * @method getCarrierRegionV2()
 * @method getCarrierRegion()
 * @method getMccMnc()
 */
class UserModel extends MainMapper
{
    protected array $data;

    public const MAP = [
        'username' => 'string',
        'device_id' => 'string',
        'useragent' => 'string',
        'install_id' => 'string',
        'openudid' => 'string',
        'device_brand' => 'string',
        'device_type' => 'string',
        'resolution' => 'int',
        'dpi' => 'int',
        'carrier_region_v2' => 'int',
        'carrier_region' => 'string',
        'mcc_mnc' => 'string'
    ];

    function extractCookies($string) {

        if (!file_exists($string))
        {
            return '';
        }

        $lines = explode(PHP_EOL, file_get_contents($string));
        $cookies = '';
        foreach ($lines as $line) {

            // detect httponly cookies and remove #HttpOnly prefix
            if (substr($line, 0, 10) == '#HttpOnly_') {
                $line = substr($line, 10);
                $cookie['httponly'] = true;
            } else {
                $cookie['httponly'] = false;
            }

            // we only care for valid cookie def lines
            if( strlen( $line ) > 0 && $line[0] != '#' && substr_count($line, "\t") == 6) {

                // get tokens in an array
                $tokens = explode("\t", $line);

                // trim the tokens
                $tokens = array_map('trim', $tokens);

                // Extract the data
                $cookie['domain'] = $tokens[0]; // The domain that created AND can read the variable.
                $cookie['flag'] = $tokens[1];   // A TRUE/FALSE value indicating if all machines within a given domain can access the variable.
                $cookie['path'] = $tokens[2];   // The path within the domain that the variable is valid for.
                $cookie['secure'] = $tokens[3]; // A TRUE/FALSE value indicating if a secure connection with the domain is needed to access the variable.

                $cookie['expiration-epoch'] = $tokens[4];  // The UNIX time that the variable will expire on.
                $cookie['name'] = urldecode($tokens[5]);   // The name of the variable.
                $cookie['value'] = urldecode($tokens[6]);  // The value of the variable.

                // Convert date to a readable format
                $cookie['expiration'] = date('Y-m-d h:i:s', $tokens[4]);

                // Record the cookie.
                $cookies .= urldecode($tokens[5]).'='.urldecode($tokens[6]).'; ';
            }
        }

        return $cookies;
    }



}
