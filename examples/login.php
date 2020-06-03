<?php
require '../vendor/autoload.php';
$deviceInfo = [
    'device_brand' => 'TCL',
    'device_type' => '9027F',
    'openudid' => '71c300d58b222214',
    'device_id' => '6735782704719316486',
    'android_id' => '71c300d58b222214',
    'install_id' => '6832794245474256645',
    'useragent' => 'com.zhiliaoapp.musically/2019092901 (Linux; U; Android 10; tr_TR; SM-A505F; Build/QP1A.190711.020; Cronet/58.0.2991.0)'
];
try{
    $username = 'kervanyoluboklu';
    $password = 'qweqwe11';
    $proxy    = 'webgrambxf82a:f413c712d12e30a0b413@ianaliz.com:10571';


//    $str = "manifest_version_code=2019092901&_rticket=1591185471023&app_language=tr&app_type=normal&iid=6834068424990590725&channel=googleplay&device_type=SM-A505F&language=tr&locale=tr-TR&resolution=1080*2131&openudid=71c300d58b222214&update_version_code=2019092901&ac2=wifi&sys_region=TR&os_api=29&uoo=0&is_my_cn=0&timezone_name=Europe%2FIstanbul&dpi=420&carrier_region=TR&ac=wifi&device_id=6735782704719316486&pass-route=1&mcc_mnc=28601&os_version=10&timezone_offset=10800&version_code=130211&carrier_region_v2=286&app_name=musical_ly&ab_version=13.2.11&version_name=13.2.11&device_brand=samsung&ssmix=a&pass-region=1&device_platform=android&build_number=13.2.11&region=TR&aid=1233&ts=1591185469";
//    (parse_str(urldecode($str),$out));
//    print_r($out);
//    foreach ($out as $key => $value){
//        print_r('$this->addParam(\''.$key.'\',\''.$value.'\');'."\n");
//    }
//    exit;

    $t = new \TikTokAPI\TikTok($username, $password,$deviceInfo);
    print_r($t->registerDevice());
    //$t->setProxy($proxy);
//    $t->changeDeviceInfo();
//
//////    exit;
////    $t->changeDeviceInfo();
//    $login = $t->login();
//    print_r($login);
//    if (isset($login['data']['error_code']) && (int)$login['data']['error_code'] === 1105)
//    {
//        $verifyTime = time();
//        print_r($t->report('h5_touchstart',$verifyTime));
//        $captcha = $t->getCaptcha();
//        print_r($captcha);
//        if (isset($captcha['ret']) && (int)$captcha['ret'] === 200)
//        {
//            sleep(3);
//            print_r($t->report('h5_touchend',$verifyTime));
//            sleep(2);
//            $solve = $t->solveCaptcha($captcha['data']['id'],$captcha['data']['question']['url1'],$captcha['data']['question']['url2'],(int)$captcha['data']['question']['tip_y']);
//            print_r($solve);
//            if (isset($solve['ret']) && (int)$solve['ret'] === 200)
//            {
//                print_r($t->login());
//            }
//        }
//    }
//        $t->changeDeviceInfo();
//        $f = $t->follow('MS4wLjABAAAADzdlzlUKWLZvVqyo0s9Wc2ySX2pmJqKRWcDRpvm4ifdmbSyObPcwbq6DGdy9ljpr');
//        print_r($f);

        $like = $t->like('6803512377544396038');
        print_r($like);

}catch (Exception $e)
{
    print_r($e->getMessage());
    print_r($e->getFile());
}