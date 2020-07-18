<?php

require '../vendor/autoload.php';

$username = 'darbelivazocuolu';
$email = $username."@smm.tc";

try{
    $t = new \TikTokAPI\TikTok($username,'qweqwe11*');
    $isReg = $t->checkEmailRegistered($email);

    if (isset($isReg['data']['error_code']))
    {
        $captcha = $t->getCaptcha();
        $captcha->getData()->getQuestion();
        $solve = $t->solveCaptcha($captcha->getData()->getId(),$captcha->getData()->getQuestion()->getUrl1(),$captcha->getData()->getQuestion()->getUrl2(),$captcha->getData()->getQuestion()->getTipY());
        if ($solve->getMsgType() === 'success')
        {
            $isReg = $t->checkEmailRegistered($email);
            if ((int)$isReg['data']['is_registered'] === 0)
            {
                print_r($t->register($email,'qweqwe11*'));
            }
        }
    }
}catch (Exception $e)
{
    print_r($e->getMessage());
}