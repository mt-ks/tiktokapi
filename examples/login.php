<?php
require '../vendor/autoload.php';

try{
    $username = 'felokustaa';
    $password = 'qweqwe11';
    $proxy    = 'webgrambxf82a:f413c712d12e30a0b413@ianaliz.com:5654';

    $t = new \TikTokAPI\TikTok($username, $password);
//    $t->setProxy($proxy);
////    $t->changeDeviceInfo();
////    exit;
//    //$t->changeDeviceInfo();
//    $login = $t->login();
//    print_r($login);
//    if (isset($login['data']['error_code']) && (int)$login['data']['error_code'] === 1105)
//    {
//        $captcha = $t->getCaptcha();
//        if (isset($captcha['ret']) && (int)$captcha['ret'] === 200)
//        {
//            $solve = $t->solveCaptcha($captcha['data']['id'],$captcha['data']['question']['url1'],$captcha['data']['question']['url2'],45);
//            print_r($solve);
//            sleep(3);
//            if (isset($solve['ret']) && (int)$solve['ret'] === 200)
//            {
//                print_r($t->login());
//            }
//        }
//    }

        $t->changeDeviceInfo();
        $f = $t->follow('MS4wLjABAAAADzdlzlUKWLZvVqyo0s9Wc2ySX2pmJqKRWcDRpvm4ifdmbSyObPcwbq6DGdy9ljpr');
        print_r($f);

    //    $like = $t->like('6820083130800540933');
    //    print_r($like);

}catch (Exception $e)
{
    print_r($e->getMessage());
    print_r($e->getFile());
}