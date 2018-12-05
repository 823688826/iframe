<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-4-16 0016
 * Time: 16:06
 */
class FactoryController extends Container {

    private static $instance;

        protected static $userInfo;

        public function __construct()
    {

        if(!self::$instance) {
            self::$instance =& $this;
        }
        //加载loader库
        $this->load = self::$classCollect['loaderlibrary'];
        $this->load->factory = self::$instance;
        //释放loader
        unset(self::$classCollect['loaderlibrary']);

        //加载视图库
        $this->loadView = self::$classCollect['factoryviewlibrary'];
        //释放视图库
        unset(self::$classCollect['factoryviewlibrary']);

        //加载路由情况的ku
        $this->route = self::$classCollect['route'];
        //释放路由库
        unset(self::$classCollect['route']);

        $this->input = self::$classCollect['inputlibrary'];
        //释放input库
        unset(self::$classCollect['inputlibrary']);

        $this->check = self::$functionList['checkParam'];
        unset(self::$functionList['checkParam']);

        $this->load->loadLibrary('ApiLibrary');
    }

    public static function &get_instance()
    {
        return self::$instance;
    }

    public function checkParam($param = [])
    {
        return call_user_func_array($this->check,$param);
    }

    public static function getUserInfo()
    {
        return self::$userInfo;
    }
}