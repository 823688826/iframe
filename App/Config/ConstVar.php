<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-4-2 0002
 * Time: 20:36
 */
//根目录

//系统目录
define('__SYS__',__ROOT__."/System");

//APP目录
define('__APP__',__ROOT__."/App");

//资源目录
define("__RESOURCE__",__ROOT__."/Public");

//拓展目录
define('__VENDOR___',__ROOT__."/vendor");

define('__SYSLIB__',__ROOT__."/System/Library");

//控制器目录
define('__CONTROLLER__',__ROOT__.'/App/Controller/');

//视图目录
define('__VIEW__',__ROOT__.'/App/View/');

//没有错误
define('OK_NO',0);
//添加错误
define('ADD_ERRNO',1);
//修改错误
define('UPDATE_ERRNO',2);
//删除错误
define('DELETE_ERROR',3);
//参数错误
define('PARAM_ERROR',4);
//其他错误
define('OTHER_RROR',5);


