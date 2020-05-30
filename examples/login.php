<?php
require '../vendor/autoload.php';

try{
    $username = '35denizkaraca35';
    $password = '9BIGMKhUCc2u';
    $proxy    = 'webgrambx1acb:459c0f2131011f12c1ea@ianaliz.com:3211';

    $t = new \TikTokAPI\TikTok($username, $password);
    $t->setProxy($proxy);
    //$t->changeDeviceInfo();
    $login = $t->login();
    print_r($login);
    if (isset($login['data']['error_code']) && (int)$login['data']['error_code'] === 1105)
    {
        $captcha = $t->getCaptcha();
        if (isset($captcha['ret']) && (int)$captcha['ret'] === 200)
        {
            $solve = $t->solveCaptcha($captcha['data']['id'],$captcha['data']['question']['url1'],$captcha['data']['question']['url2'],45);
            if (isset($solve['ret']) && (int)$solve['ret'] === 200)
            {
                print_r($t->login());
            }
        }
    }

    //    $f = $t->follow('MS4wLjABAAAAcPFyhwpBXODcu5Q_RRfmbyuGOHYqwJBQJTG9o3Tumy3Bn9bZPg_UjXEPXoyJmd2h');
    //    print_r($f);

    //    $like = $t->like('6820083130800540933');
    //    print_r($like);

}catch (Exception $e)
{
    print_r($e->getMessage());
}