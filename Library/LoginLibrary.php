<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-16 0016
 * Time: 10:07
 */
class LoginLibrary{

    //制作登陆信息
    public function make_login($username,$password)
    {
        if(!$username || !$password)
        {
            return false;
        }

        $rand = [];
        //获取浏览器类型
        $user_agent = 111;

        $rand['user_agent'] = $user_agent;
        $rand['username'] = $username;
        $rand['password'] = $password;
        $rand['make_time'] = time();

        $json_str = json_encode($rand);

        $encrypt_str = encrypt($json_str);

        $_SESSION['login_token'] = $encrypt_str;

        return true;
    }

    public function get_token()
    {
        $login_token = easySession('login_token');
        if(!$login_token)
        {
            return false;
        }

        $token = decrypt($login_token);

        if(!$token)
        {
            return false;
        }

        $login_array = json_decode($token,1);

        return $login_array ? $login_array : [];
    }
}