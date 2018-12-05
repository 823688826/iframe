<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-4-16 0016
 * Time: 18:17
 */
/**
 * 此文件用来注入依赖
 */

//引入数据库trait
$loaderInstance->loadTrait("PdoDriver");

session_start();

include_once $_SERVER['DOCUMENT_ROOT']."/Core/CheckParam.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/Core/LoadFile.php";


//注入方法

LoaderLibrary::injectContainerFun('test', function ($a, $b) {
    var_dump($b);
    var_dump($a);
    die;
});
