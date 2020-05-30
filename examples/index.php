<?php
require_once '../vendor/autoload.php';
//$url   = 'https://api2.musical.ly/passport/user/login/';
//$query = '?manifest_version_code=2019091803&_rticket='. strval(round(microtime(true) * 1000)) .'&current_region=TR&app_language=tr&app_type=normal&iid=6831924580761323269&channel=googleplay&device_type=SM-A505F&language=tr&locale=tr-TR&resolution=1080*2131&openudid=71c300d58b222214&update_version_code=2019091803&ac2=wifi&sys_region=TR&os_api=29&uoo=0&is_my_cn=0&timezone_name=Europe%2FIstanbul&dpi=420&residence=TR&carrier_region=TR&ac=wifi&device_id=6735782704719316486&pass-route=1&mcc_mnc=28601&os_version=10&timezone_offset=10800&version_code=130103&carrier_region_v2=286&app_name=musical_ly&ab_version=13.1.3&version_name=13.1.3&device_brand=samsung&ssmix=a&pass-region=1&device_platform=android&build_number=13.1.3&region=TR&aid=1233&ts='. strval(time());
//$body  = 'mix_mode=1&username=68712b6e76&email=&mobile=&account=&password=343736313033&multi_login=0&captcha=';
//
//$headers = [
//    'User-Agent: com.zhiliaoapp.musically/2019091803 (Linux; U; Android 10; tr_TR; SM-A505F; Build/QP1A.190711.020; Cronet/58.0.2991.0)',
////    'X-Khronos: 1590748332',
////    'X-Gorgon: 83008c76000016f961ab884c20c24732d758389325da82d17041'
//];
//////
//$token = new \TikTokAPI\Encryption\CreateToken($url,$query,$body,$headers);
//print_r($token);
//exit;
//array_push($headers,'X-Gorgon: '.$token->getXGorgon(),'X-Khronos: '.$token->getXKhronos());
////
//
//$c = curl_init();
//$o = [
//    CURLOPT_URL => $url.$query,
//    CURLOPT_RETURNTRANSFER => TRUE,
//    CURLOPT_HEADER => TRUE,
//    CURLOPT_FOLLOWLOCATION => TRUE,
//    CURLOPT_SSL_VERIFYPEER => FALSE,
//    CURLOPT_SSL_VERIFYHOST => FALSE,
////    CURLOPT_ENCODING => 'gzip, deflate',
//    CURLOPT_COOKIEFILE      => 'cookie.txt',
//    CURLOPT_COOKIEJAR => 'cookie.txt',
//    CURLOPT_POSTFIELDS => $body,
//    CURLOPT_POST => TRUE,
//    CURLOPT_HTTPHEADER => $headers
//];
//curl_setopt_array($c,$o);
//$r = curl_exec($c);
//curl_close($c);
//print_r($o);
//print_r($r);



$deviceInfo = [
    'device_brand' => 'TCL',
    'device_type' => '9027F',
    'openudid' => '71c300d58b222214',
    'device_id' => '6832320459406181894',
    'android_id' => '71c300d58b222214',
    'iid' => '6832320786397742854',
    'useragent' => 'com.zhiliaoapp.musically/2019091803 (Linux; U; Android 10; tr_TR; SM-A505F; Build/QP1A.190711.020; Cronet/58.0.2991.0)'
];

try {
    $username = 'buketamkoparancik'.rand(9,999999);
    $t = new \TikTokAPI\TikTok($username, 'qweqwe11', $deviceInfo);
    $t->setProxy('webgrambx418b:fcf613af997a11ad11d1@ianaliz.com:20685');

    $register = $t->registerDevice();


    $t->storage->set('device_type',$register->getDeviceType())
        ->set('device_brand',$register->getDeviceBrand())
        ->set('openudid',$register->getOpenUDID())
        ->set('device_id',$register->getDeviceId())
        ->set('useragent',$register->getUseragent())
        ->set('iid',$register->getInstallId());


    $yr = $t->registerVerifyAge();
    print_r($yr);

    $r = $t->checkEmailRegistered($username.'@smm.tc');
    print_r($r);
    if (isset($r['data']['error_code']) && (int)$r['data']['error_code'] === 1105)
    {
        $captcha = $t->getCaptcha();
        if (isset($captcha['ret']) && (int)$captcha['ret'] === 200)
        {
            $solve = $t->solveCaptcha($captcha['data']['id'],$captcha['data']['question']['url1'],$captcha['data']['question']['url2'],45);
            if (isset($solve['ret']) && (int)$solve['ret'] === 200)
            {
                $r = $t->checkEmailRegistered($username.'@gmail.com');
                print_r($r);
            }
        }
    }
    sleep(5);
    $r = $t->register($username.'@gmail.com','qweqwe11');
    print_r($r);

    $captcha = $t->getCaptcha();
    print_r($captcha);
    if (isset($captcha['ret']) && (int)$captcha['ret'] === 200)
    {
        $solve = $t->solveCaptcha($captcha['data']['id'],$captcha['data']['question']['url1'],$captcha['data']['question']['url2'],45);
        if (isset($solve['ret']) && (int)$solve['ret'] === 200)
        {
            $r = $t->register($username.'@gmail.com','qweqwe11');
            print_r($r);
        }
    }

} catch (Exception $e) {
    print_r($e->getMessage());
    print_r($e->getFile());
    print_r($e->getLine());
}
