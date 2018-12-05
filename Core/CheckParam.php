<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-5-15 0015
 * Time: 18:42
 */

//注入检查类包
FactoryController::injectContainerFun('checkParam',
    $check = function ($param, $type = 'get',$xss=true) use ($inputInstance) {
        if ($type != 'get' && $type != 'post') {
            productError("type must be get or post");
        }
        if ($type == 'get') {
            $globalVar = $_GET;
        } else {
            $globalVar = $_POST;
        }
        $data = [];
        foreach ($param as $key => $value) {

            if (isset($globalVar[$value]) && ($globalVar[$value] !== "")) {
                $data[$value] = $inputInstance->$type($value,$xss);

            } else {
                return false;
            }
        }
        return $data;
    });