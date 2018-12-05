<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-16 0016
 * Time: 9:40
 */
class ApiLibrary
{

    /**
     * Description:输出
     */
    public function api_echo($code, $msg)
    {
        $oldMsg = $msg;//将老的内存赋值一块地址
        //进行中英文翻译

        if (is_string($msg) && $msg) {
            $user_lang = get_instance()->input->get('EN');
            get_instance()->load->loadLibrary('TranslatorLibrary');
            $msg = get_instance()->TranslatorLibrary->make_translator($user_lang, $msg);
        }

        //进行错误日志记录
        if ($code != 0) {
            get_instance()->load->model("ErrorLogModel");
            get_instance()->load->loadLibrary("LoginLibrary");
            $login_token = get_instance()->LoginLibrary->get_token();
            if ($login_token) {//如果是管理员
                $userInfo = get_instance()::getUserInfo();
                if ($userInfo) {
                    $base_info = [];
                    $base_info["enterprise_uuid"] = $userInfo["enterprise_uuid"];
                    $base_info["operation_uuid"] = $userInfo["uuid"];
                    $base_info['type'] = 1;
                    if ($msg) {//如果说翻译信息存在
                        $base_info["content"] = $msg;
                    } else {
                        if ($user_lang == 0) {
                            $addMsg = "操作发生错误";
                        } else {
                            $addMsg = "Operation error";
                        }
                        $base_info["content"] = $addMsg;
                    }
                    get_instance()->ErrorLogModel->add($base_info);
                }
            }
        }
        if ($msg) {
            $oldMsg = $msg;
        }

        ob_clean();
        exit(json_encode(['errcode' => $code, 'errmsg' => $oldMsg], JSON_UNESCAPED_UNICODE));
    }


    /**
     * 防刷新处理
     */
    public function antiBrush()
    {
        $ip = $_SERVER['REMOTE_ADDR'];//获取当前访问者的ip

        $time = time();//当前时间搓

        $allowtime = 30;//防刷新时间秒
        $IntervalTime = $time - $allowtime;//间隔时间

        $allownum = 10;//防刷新次数
//        echo "allownum:$allownum";
//        echo "<hr/>";
        //实例化model
        get_instance()->load->model("IpconfigModel");
        $model = get_instance()->IpconfigModel;
        $model->addlog($ip);
//        echo "<hr/>";
        $count=$model->setlog($ip,$IntervalTime);
//        echo  $count;
        if($count>$allownum){
//            exit("警告！");
//            header("HTTP/1.1 404 Not Found");
//            header("Status: 404 Not Found");
            @header("http/1.1 404 not found");
            @header("status: 404 not found");
            echo 'echo 404';//直接输出页面错误信息
            exit();
        }else {
            exit("欢迎访问！");
        }
    }

}