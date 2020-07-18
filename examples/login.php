<?php

use TikTokAPI\TikTok;

require '../vendor/autoload.php';
$deviceInfo = [
    'device_brand' => 'TCL',
    'device_type' => '9027F',
    'openudid' => '71c300d58b222214',
    'device_id' => '6735782704719316486',
    'android_id' => '71c300d58b222214',
    'install_id' => '6832794245474256645',
    'useragent' => 'com.zhiliaoapp.musically/2019092901 (Linux; U; Android 10; tr_TR; SM-A800; Build/QP1A.190711.020; Cronet/58.0.2991.0)'
];
try{
    $username = 'kervanyoluboklu';
    $password = 'qweqwe11';
    $proxy    = 'webgrambxf82a:f413c712d12e30a0b413@ianaliz.com:10571';


    $t = new TikTok($username, $password);

//    $login = $t->login();
//    print_r($login->asArray());
//    if ($login->getMessage() === 'error' && $login->getData()->getErrorCode() === 1105)
//    {
//        $captcha = $t->getCaptcha();
//        $solve   = $t->solveCaptcha($captcha->getData()->getId(),
//            $captcha->getData()->getQuestion()->getUrl1(),
//            $captcha->getData()->getQuestion()->getUrl2(),
//            $captcha->getData()->getQuestion()->getTipY());
//
//        if ($solve->getMsgType() === 'success')
//        {
//            $login = $t->login();
//            if ($login->getMessage() === 'success')
//            {
//                // User authenticated
//                print_r($login->getData()->getScreenName());
//            }else{
//                print_r($login->getData()->getDescription());
//            }
//        }
//    }


//        $t->changeDeviceInfo();
        $like = $t->like('6819630756407643397');
        print_r($like->asArray());

}catch (Exception $e)
{
    print_r($e->getMessage());
    print_r($e->getFile());
}