<?php

class TiktokApi{
    public $api_url;
    public $cookies_dir;
    public $setUser;
    public function __construct()
    {
        $this->api_url = 'https://api2.musical.ly/';
        $this->cookies_dir = realpath('.').'/application/tiktok_cookies/';
    }

    public function login($username,$password,$captcha = null)
    {
        $this->setUser = $this->cookies_dir.$username.'-cookie.txt';
        return $this->request('passport/user/login/?'.$this->requestArray(),array(
            'mix_mode'  => 1,
            'username'  => $username,
            'email'     => '',
            'mobile'    => '',
            'account'   => '',
            'password'  => $password,
            'captcha'   => $captcha,
        ));
    }



    public function SetUser($username)
    {
        return $this->setUser = $this->cookies_dir.$username.'-cookie.txt';
    }

    public function userInfo($user_id){
        return $this->request('aweme/v1/user/?user_id='.$user_id.'&'.$this->requestArray());
    }

    public function userMedias($user_id,$max_cursor = 0){
        return $this->request('aweme/v1/aweme/post/?max_cursor='.$max_cursor.'&user_id='.$user_id.'&count=10&retry_type=no_retry&'.$this->requestArray());
    }

    public function userFollowers($user_id,$max_time = null){
        if($max_time == null){ $max_time = (time() *1000); }
        return $this->request('aweme/v1/user/follower/list/?user_id='.$user_id.'&count=10&max_time='.$max_time.'&retry_type=no_retry&'.$this->requestArray());
    }

    public function userFollowing($user_id,$max_time = null){
        if($max_time == null){ $max_time = (time() *1000); }
        return $this->request('aweme/v1/user/following/list/?user_id='.$user_id.'&count=10&max_time='.$max_time.'&retry_type=no_retry&'.$this->requestArray());
    }

    public function getVideoDetail($video_id){
        return $this->request('aweme/v1/aweme/detail/?aweme_id='.$video_id.'&'.$this->requestArray());
    }

    public function getComments($video_id,$cursor = 0){
        return $this->request('aweme/v1/comment/list/?aweme_id='.$video_id.'&comment_style=2&digged_cid&insert_cids&?count=100&cursor='.$cursor.'&'.$this->requestArray());
    }

    public function follow($id){
        return $this->request('aweme/v1/commit/follow/user/?user_id='.$id.'&type=1&retry_type=no_retry&from=3&'.$this->requestArray());
    }

    public function Verify(){
        return $this->outRequest('https://verification-va.byteoversea.com/get?'.$this->requestArray());
    }

    public function getUserFollowers(){

    }

    public function PopularCategory(){
        return $this->request('aweme/v1/category/list/?'.$this->requestArray());
    }

    public function ForYou(){
        return $this->request('aweme/v1/feed/?count=25&offset=0&max_cursor=0&min_cursor=0&type=0&is_cold_start=1&pull_type=0&req_from&'.$this->requestArray());
    }

    public function searchUser($username){
        return $this->request('aweme/v1/discover/search/?cursor=0&keyword='.$username.'&count=10&type=1&hot_search=0&'.$this->requestArray());
    }

    public function searchHashtag($hashtag){
        return $this->request('aweme/v1/challenge/search/?cursor=0&keyword='.$hashtag.'&count=10&type=1&hot_search=0&'.$this->requestArray());
    }

    public function listHashtag($hashtagID,$cursor = 0){
        return $this->request('aweme/v1/challenge/aweme/?ch_id='.$hashtagID.'&count=20&offset=0&max_cursor=0&type=5&query_type=0&is_cold_start=1&pull_type=1&cursor='.$cursor.'&'.$this->requestArray());
    }


    public function headers(){
        return array(
            "Host" => $this->api_url,
            'X-SS-TC' => "0",
            'User-Agent' => "com.zhiliaoapp.musically/2018090613 (Linux; U; Android 8.0.0; tr_TR; TA-1020; Build/O00623; Cronet/58.0.2991.0)",
            'Accept-Encoding' => "gzip",
            'Connection' => "keep-alive",
            'X-Tt-Token' => "",
            'sdk-version' => "1",
        );
    }

    public function request($endpoint,$post = null){


        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $this->api_url.$endpoint,
            CURLOPT_USERAGENT => 'com.zhiliaoapp.musically/2018090613 (Linux; U; Android 8.0.0; tr_TR; TA-1020; Build/O00623; Cronet/58.0.2991.0)',
            CURLOPT_REFERER => $this->api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEFILE => $this->setUser,
            CURLOPT_COOKIEJAR => $this->setUser,
            CURLOPT_HTTPHEADER => $this->headers()
        );
        if($post){


            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $post;
        }
        curl_setopt_array($curl,$options);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public function outRequest($page,$post = null){
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => $page,
            CURLOPT_USERAGENT => 'com.zhiliaoapp.musically/2018090613 (Linux; U; Android 8.0.0; tr_TR; TA-1020; Build/O00623; Cronet/58.0.2991.0)',
            CURLOPT_REFERER => $this->api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEFILE => $this->setUser,
            CURLOPT_COOKIEJAR => $this->setUser,
            CURLOPT_HTTPHEADER => $this->headers()
        );
        if($post){
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $post;
        }
        curl_setopt_array($curl,$options);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

    public function requestArray(){
        $items = array(
            'gaid'                  => '74e5ef48-845d-4056-9ca6-59f556f48ec0',
            'ad_user_agent'         => 'Dalvik%2F2.1.0+%28Linux%3B+U%3B+Android+5.1.1%3B+GT-N7100+Build%2FLMY48B%29',
            'filter_warn'           => 0,
            'bid_ad_params'         => '',
            'android_id'            => 'a0b7148b6e5edcb1',
            'ad_personality_mode'   => '1',
            'ts'                    => time(),
            'js_sdk_version'        => '',
            'app_type'              => 'normal',
            'os_api'                => '22',
            'device_type'           => 'GT-N7100',
            'ssmix'                 => 'a',
            'manifest_version_code' => '2019011531',
            'dpi'                   => '320',
            'carrier_region'        => 'US',
            'carrier_region_v2'     => '286',
            'app_name'              => 'musical_ly',
            'version_name'          => '9.9.0',
            'timezone_offset'       => '7200',
            'pass-route'            => '1',
            'pass-region'           => '1',
            'is_my_cn'              => 0,
            'fp'                    => '',
            'ac'                    => 'wifi',
            'update_version_code'   => '2019011531',
            'channel'               => 'googleplay',
            '_rticket'              => time(),
            'device_platform'       => 'android',
            'iid'                   => '6650110848834635526',
            'build_number'          => '9.9.0',
            'version_code'          => '990',
            'timezone_name'         => 'America/Indiana/Petersburg',
            'account_region'        => 'V',
            'openudid'              => 'a0b7148b6e5edcb1',
            'device_id'             => '6638361694995465733',
            'sys_region'            => 'US',
            'app_language'          => 'us',
            'resolution'            => '720*1280',
            'os_version'            => '5.1.1',
            'device_brand'          => 'samsung',
            'language'              => 'us',
            'aid'                   => '1233',
            'mcc_mnc'               => '28601',
            'as'                    => 'a125c795f154dc43080766',
            'cp'                    => '7c4ac95d1d855f36e1amOq',
            'mas'                   =>  md5(sha1(time()))
        );

        foreach ($items as $key => $item){
            $packet[] = $key.'='.$item;
        }
        $implode = implode('&',$packet);
        return $implode;
   }


/*
 * https://api2-16-h2.musical.ly/aweme/v1/feed/?type=0&max_cursor=0&min_cursor=0&count=6&volume=0.4666666666666667&pull_type=0&req_from=enter_auto&gaid=74e5ef48-845d-4056-9ca6-59f556f48ec0&ad_user_agent=Dalvik%2F2.1.0+%28Linux%3B+U%3B+Android+5.1.1%3B+GT-N7100+Build%2FLMY48B%29&filter_warn=0&bid_ad_params&android_id=a0b7148b6e5edcb1&ad_personality_mode=1&ts=1549302090&js_sdk_version=&app_type=normal&os_api=22&device_type=GT-N7100&ssmix=a&manifest_version_code=2019011531&dpi=320&carrier_region=TR&region=TR&carrier_region_v2=286&app_name=musical_ly&version_name=9.9.0&ab_version=9.9.0&timezone_offset=7200&pass-route=1&pass-region=1&is_my_cn=0&fp=&ac=wifi&update_version_code=2019011531&channel=googleplay&_rticket=1549302090028&device_platform=android&iid=6650110848834635526&build_number=9.9.0&version_code=990&timezone_name=Europe%2FIstanbul&account_region=TR&openudid=a0b7148b6e5edcb1&device_id=6638361694995465733&sys_region=TR&app_language=tr&resolution=720*1280&os_version=5.1.1&device_brand=samsung&language=tr&aid=1233&mcc_mnc=28601&as=a1iosdfgh&cp=androide1
 * https://api2.musical.ly/api/ad/splash/musical/v14/?_unused=0&carrier=TURKCELL&mcc_mnc=28601&ad_area=720x1230&sdk_version=1.6.3&os_api=22&device_platform=android&os_version=5.1.1&display_density=720x1280&dpi=320&device_brand=samsung&device_type=GT-N7100&bh=245&display_dpi=320&density=2.0&ac=wifi&channel=googleplay&aid=1233&app_name=musical_ly&update_version_code=2019011531&version_code=990&version_name=9.9.0&manifest_version_code=2019011531&language=tr&iid=6650110848834635526&device_id=6638361694995465733&openudid=a0b7148b6e5edcb1&ab_version=9.9.0&user_period=0&user_mode=0&user_id=6653519491903684614&gaid=74e5ef48-845d-4056-9ca6-59f556f48ec0&android_id=a0b7148b6e5edcb1&ad_user_agent=Dalvik%2F2.1.0+%28Linux%3B+U%3B+Android+5.1.1%3B+GT-N7100+Build%2FLMY48B%29&retry_type=first_retry&app_language=tr&region=TR&sys_region=TR&carrier_region=TR&carrier_region_v2=286&build_number=9.9.0&timezone_offset=7200&timezone_name=Europe%2FIstanbul&is_my_cn=0&fp=&account_region=TR&pass-region=1&pass-route=1&ssmix=a&resolution=720*1280&_rticket=1549302067863&ts=1549298850&as=a125d6d5228a0c4c584155&cp=6ea2c95029885acae1OeWi&mas=01a07d4924a9975b3f3286abd8b865d611acac8c2c1cacc62cc60c
    public function requestArray(){
        $items = array(
            'app_language' => 'tr',
            'language'     => 'tr',
            'region'       => 'tr',
            'app_type'     => 'normal',
            'sys_region'   => 'TR',
            'carrier_region' => 'TR',
            'carrier_region_v2' => '28601',
            'is_my_cn'          => 0,
            'fp' => "",
            'build_number' =>  '9.1.0',
            'account_region' => 'TR',
            'iid' => '6620659482206930694',
            'ac' => 'wifi',
            'channel' => 'googleplay',
            'aid' => '1233',
            'app_name' => 'musical_ly',
            'version_code' => '910',
            'version_name' => '9.1.0',
            'device_id' => '6594726280552547846',
            'device_platform' => 'android',
            'ssmix' => 'a',
            'device_type' => 'Pixel',
            'device_brand' => 'Nokia',
            'os_api' => '23',
            'os_version' => '7.1.2',
            'openudid' => 'b307b864b574e818',
            'manifest_version_code' => '2018111632',
            'resolution' => '720*1280',
            'dpi' => '420',
            'update_version_code' => '2018111632',
            '_rticket' => time() * 1000,
            'timezone_offset' => 36000,
            'ts' => time() * 1000,
            'as' => 'a1qwert123',
            'cp' => 'cbfhckdckkde1'
        );
        foreach ($items as $key => $item){
            $packet[] = $key.'='.$item;
        }
        $implode = implode('&',$packet);
        return $implode;
    }
*/


}



?>