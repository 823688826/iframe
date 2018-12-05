<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/25
 * Time: 9:12
 */
header('Content-Type: text/html; charset=utf-8');

$result = '良好';
$dbname = '';
$username = '';
$password = '';
$versionNum = 0;

//检测是否支持 ini_get 函数

if (!isfun('ini_get')) {
    die("您的环境不支持 ini_get ,检测停止，请修改配置文件");
}

if (!isfun('extension_loaded')) {
    die("您的环境不支持 extension_loaded ,检测停止，请修改配置文件");
}

$phpVersion = PHP_VERSION;
$phpVersionState = false;

if($phpVersion == '5.6.28' || $phpVersion == '7.0.13'){
    $phpVersionState = true;
}


//if (version_compare("5.6.28", PHP_VERSION, "<")) {
//    $phpVersionState = true;
//} else if (version_compare("7.0.13", PHP_VERSION, "<")) {
//    $phpVersionState = true;
//} else {
//    $result = '不可运行';
//}

//服务器 系统类型

$serverOs = php_uname();

//服务器 软件类型

$serverSoftware = $_SERVER["SERVER_SOFTWARE"];

//PHP 运行方式
$runStyle = php_sapi_name();


//mysql 版本
$mysqlVersion = isset($_GET['version']) ? $_GET['version'] : "--";

$hasTested = false;
$mysqlVersioArray = explode('.', $mysqlVersion);
if (is_array($mysqlVersioArray) and (count($mysqlVersioArray) >= 3)) {
    if (($mysqlVersioArray[0] >= 5) && ($mysqlVersioArray[1] >= 5)) {
        $mysqlState = true;
        $result = "可以运行";

    } else {
        $mysqlState = false;
        $result = "不可运行";
    }
    $hasTested = true;
}


$tmp = array(
    'memTotal', 'memUsed', 'memFree', 'memPercent',
    'memCached', 'memRealPercent',
    'swapTotal', 'swapUsed', 'swapFree', 'swapPercent'
);
//MySQL检测
if (!empty($_POST)) {
    if ($_POST['act'] == 'MySQL检测') {
        $host = isset($_POST['host']) ? trim($_POST['host']) : '';
        $port = isset($_POST['port']) ? (int)$_POST['port'] : '';
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $host = preg_match('~[^a-z0-9\-\.]+~i', $host) ? '' : $host;
        $port = intval($port) ? intval($port) : '';
        $username = preg_match('~[^a-z0-9\_\-]+~i', $username) ? '' : htmlspecialchars($username);
        $password = is_string($password) ? htmlspecialchars($password) : '';
        $mysqli = extension_loaded('mysqli');
        $pdo = extension_loaded('pdo_mysql');


        if (!empty($mysqli)) {
            $mysqli = new mysqli($host . ":" . $port, $username, $password);
            if (!mysqli_connect_errno()) {
                $mysqlVersion = $mysqli->server_info;
                $mysqlVersioArray = explode('.', $mysqlVersion);
                if (is_array($mysqlVersioArray) and (count($mysqlVersioArray) >= 3)) {
                    if (($mysqlVersioArray[0] >= 5) && ($mysqlVersioArray[1] >= 5)) {
                        $mysqlState = true;

                    } else {
                        $mysqlState = false;
                    }

                }
                echo "<script>location.href=" . '"check.php?version=' . $mysqlVersion . '"' . ";
                     alert('连接到MySql数据库正常')</script>";
            } else {
                echo "<script>location.href=" . '"check.php"' . ";
                     alert('不能连接到MySql数据库')</script>";
            }
        } else if ($pdo) {
            try {

                $dbh = new PDO('mysql:host=localhost;dbname=' . $dbname, $username, $password);
                $mysqlVersion = $dbh->getAttribute(PDO::ATTR_SERVER_VERSION);
                $mysqlVersioArray = explode('.', $mysqlVersion);
                if (is_array($mysqlVersioArray) and (count($mysqlVersioArray) >= 3)) {
                    if (($mysqlVersioArray[0] >= 5) && ($mysqlVersioArray[1] >= 5)) {
                        $mysqlState = true;


                    } else {
                        $mysqlState = false;
                    }

                }

                echo "<script>location.href=" . '"check.php?version=' . $mysqlVersion . '"' . ";
                     alert('连接到MySql数据库正常')</script>";
            } catch (PDOException $e) {
                // $e->getMessage();
                echo "<script>location.href=" . '"check.php"' . ";
                     alert('不能连接到MySql数据库')</script>";
            }
        } else {
            echo "<script>location.href=" . '"check.php"' . ";
                     alert('不支持MySql数据库')</script>";
        }


    } //函数检测
    else if ($_POST['act'] == '函数检测') {
        $funRe = "函数" . $_POST['funName'] . "支持状况检测结果：" . isfun($_POST['funName']);
        echo "<script>location.href=" . '"check.php"' . ";
         alert('$funRe')</script>";

    }
}
function isfun($funName = '')
{
    if (!$funName || trim($funName) == '' || preg_match('~[^a-z0-9\_]+~i', $funName, $tmp)) return '错误';
    return (false !== function_exists($funName)) ? '√' : '×';
}

//是否支持pdo
$pdo = extension_loaded('pdo_mysql');

$pdoExtState = empty($pdo) ? "off" : "on";

//是否支持 mysqli
$mysqli = extension_loaded('mysqli');
$mysqliExt = $mysqli ? "on" : "off";
if ($mysqliExt == "on") {
    $result = "良好";
}

//错误回显是否显示
if ((int)ini_get('display_errors')) {
    $displyError = 'on';
} else {
    $displyError = 'off';
}


//short_open_tag = Off
$shortOpenTag = "on";
$shortOpenTagState = false;
if (!(int)ini_get('short_open_tag')) {
    $shortOpenTag = "off";
    $shortOpenTagState = true;
} else {
    $result = '不可运行';
}

//default_charset = UTF-8
$defaultCharSet = "UTF-8";
$defaultCharSetState = true;
if (ini_get('default_charset') != 'UTF-8') {
    $defaultCharSet = ini_get('default_charset');
    $defaultCharSetState = false;
    $result = '不可运行';
}

//allow_url_include = Off
$allowUrlInclude = "on";
$allowUrlIncludeState = false;
if (!(int)ini_get('allow_url_include')) {
    $allowUrlInclude = "off";
    $allowUrlIncludeState = true;
} else {
    $result = '不可运行';
}
// syslog
//openssl 是否打开

$openssl = "off";
$opensslState = false;
if (extension_loaded('openssl')) {
    $openssl = "on";
    $opensslState = true;
}


//是否支持redis
$redisExt = "off";
$redisExtState = false;
if (extension_loaded('redis')) {
    $redisExtState = true;
    $redisExt = "on";
}

//是否支持soap
$soapExtState = false;
$soapExt = "off";
if (extension_loaded('soap')) {
    $soapExtState = true;
    $soapExt = "on";
}

//是否支持apcu
$apcuExtState = false;
$apcuExt = "off";
if (extension_loaded('apcu')) {
    $apcuExtState = true;
    $apcuExt = "on";
}


//是否支持oci
$ociExtState = false;
$ociExt = "off";
if (extension_loaded('oci8')) {
    $ociExtState = true;
    $ociExt = "on";
}

//是否支持json
$jsonExtState = false;
$jsonExt = "off";
if (extension_loaded('json')) {
    $jsonExtState = true;
    $jsonExt = "on";
}
//GD库
$gdExt = "off";
$gdExtState = false;
if (extension_loaded('gd')) {
    $gdExtState = true;
    $gdExt = "on";
} else {
    $result = '不可运行';
}

//xml库
$xmlExt = "off";
$xmlExtState = false;
if (extension_loaded('xml')) {
    $xmlExtState = true;
    $xmlExt = "on";
} else {
    $result = '不可运行';
}

//mbstring

$mbstringExt = "off";
$mbstringExtState = false;
if (extension_loaded('mbstring')) {
    $mbstringExtState = true;
    $mbstringExt = "on";
} else {
    $result = '不可运行';
}
//icon
$iconvExt = "off";
$iconvExtState = false;
if (extension_loaded('iconv')) {
    $iconvExtState = true;
    $iconvExt = "on";
}

//curl ，支付时必须
$curlExt = "off";
$curlExtState = false;
if (extension_loaded('curl')) {
    $curlExtState = true;
    $curlExt = "on";
} else {
    $result = '不可运行';
}

//是否支持hprose
$hproseState = false;
$hproseExt = "off";
if (extension_loaded('hprose')) {
    $hproseState = true;
    $hproseExt = "on";
}


// allow_url_fopen
$allowUrlFopen = "on";
$allowUrlFopenState = true;
if (!(int)ini_get('allow_url_fopen')) {
    $allowUrlFopen = "off";
    $allowUrlFopenState = false;
    $result = '不可运行';
}

//是否记录log

$logErrors = "off";
$logErrorsState = false;
if (ini_get('log_errors')) {
    $logErrors = "on";
    $logErrorsState = true;
} else {
    $result = '不可运行';
}

//错误日志的 路径

$errorLog = (ini_get('error_log'));
$errorLogPath = dirname($errorLog);
if ((is_dir($errorLogPath)) && ($errorLog != 'syslog') && $logErrorsState) {
    $errorLogState = true;
} else {
    $errorLogState = false;
    $result = '不可运行';
}
//设置报告了那些错误

if (ini_get('error_reporting') == 0) {
    $errorReporting = "0";
    $errorReportingState = false;
    $result = "不可运行";
} else {
    $errorReporting = ini_get('error_reporting');
    $errorReportingState = true;
}

if ($displyError == 'off' && $errorReportingState && $logErrorsState) {
    $result = "可用于生产";
} else if ($displyError == 'on' && $errorReportingState && $logErrorsState) {
    $result = "可以运行";
} else {
    $result = "不可运行";
}

if(strpos(strtolower($serverSoftware),'microsoft')!==false && $apcuExtState)
{
    $result = "不可运行";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0051)http://192.168.0.90:1014/install/install.php?step=2 -->
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=7">
    <title>PHP研发部门开发环境检测报告单</title>
    <style type="text/css">
        .clr:after {
            content: " ";
            display: block;
            height: 0px;
            clear: both;
            visibility: hidden;
        }

        .clr {
            display: inline-block;
        }

        * html .clr {
            height: 0%;
        }

        .clr {
            display: block;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        body {
            background-color: #006295;
        }

        .main_box {
            width: 1000px;
            margin: 0px auto;
        }

        .ct {
            margin: 10px 0px;
            background-color: #FFFFFF;
        }

        .ct .ct_box {
            color: #fff;
            float: left;
            width: 100%;
            font-size: 12px;
            line-height: 24px;
            height: auto !important;
            height: 300px;
            min-height: 300px;
            margin: 20px 0;
            overflow-y: auto;
            border: 1px solid #67ABD2;
        }

        .nr {
            height: 300px;
            margin: 0px 20px;
        }

        .nobrd {
            border: none !important;
        }

        .nobrd .nr {
            height: auto;
        }

        .table_list {
            width: 100%;
        }

        .table_list th, .table_list td {
            border: 1px solid #67ABD2;
            padding: 10px;
            text-align: center;
            color: black
        }

        .table_list th.col {
            font-size: 14px;
            color: white
        }

        .table_list th.col1 {
            width: 120px;
        }

        .table_list th.col2 {
            width: 140px;
        }

        .table_list th.col3 {
            width: 100px;
        }

        .table_list th.col4 {
            width: 60px;
        }

        .error {
            color: red;
        }

        .correct {
            color: green
        }
    </style>
</head>
<body>
<div class="body_box">
    <div class="main_box">
        <div class="ct">
            <div class="bg_t"></div>
            <div class="clr">
                <div class="ct_box nobrd i6v">
                    <div class="nr">
                        <table cellpadding="0" cellspacing="0" class="table_list">
                            <tbody>
                            <tr>
                                <th class="col" colspan="4" bgcolor="<?=$result == '可以运行'?'#5bc4ad':'ff7758'?>">开发环境检测</th>
                            </tr>
                            <tr>
                                <th class="col1">检查项目</th>
                                <th class="col2">当前环境</th>
                                <th class="col3">建议</th>
                                <th class="col4">评分</th>
                            </tr>
                            <tr>
                                <td>操作系统</td>
                                <td><?= $serverOs ?></td>
                                <td>Windows/Linux</td>
                                <td><b class="correct">√</b></td>
                            </tr>
                            <tr>
                                <td>WEB 服务器</td>
                                <td><?= $serverSoftware ?></td>
                                <td>Apache/Nginx/IIS</td>
                                <td><b class="correct">√</b></td>
                            </tr>
                            <tr>
                                <td>PHP 版本</td>
                                <td>PHP <?= $phpVersion ?></td>
                                <td>PHP 5.6.28和7.0.13</td>
                                <td>
                                    <span>
                                        <?php if ($phpVersionState) {?>
                                            <b class="correct">√</b>
                                        <?php } else { ?>
                                            <b class="error">×</b>
                                        <?php } ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>PHP 引擎</td>
                                <td><?= $runStyle ?></td>
                                <td>fastcgi fpm isapi</td>
                                <td><b class="correct">√</b></td>
                            </tr>

                            <tr>
                                <td>display_errors</td>
                                <td>
                                    <?= $displyError ?>
                                </td>
                                <td>display_errors:On</td>
                                <td>
                                    <?php if (($displyError == 'on')) {  ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>error_reporting</td>
                                <td>
                                    <?php
                                        if ($displyError == 'off'){
                                            echo 'display_errors 未开启';
                                        }else{
                                            if ($errorReporting == 32767) {
                                                echo 'E_ALL：所有的错误和警告';
                                            } else {
                                                echo $errorReporting;
                                            }
                                        }
                                    ?>
                                </td>
                                <td>error_reporting:E_ALL</td>
                                <td>
                                    <?php if($displyError == 'off'){?>
                                        <b class="error">×</b>
                                    <?php }else{?>
                                        <?php if ($errorReporting==32767) { ?>
                                            <b class="correct">√</b>
                                        <?php } else { ?>
                                            <b class="error">×</b>
                                        <?php } ?>
                                    <?php }?>
                                </td>
                            </tr>


                            <tr>
                                <td>error_log</td>
                                <td>
                                    <?= $errorLog ?>
                                </td>
                                <td>必须开启,且不为syslog</td>
                                <td>
                                    <?php if ($errorLogState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>allow_url_fopen</td>
                                <td><?= $allowUrlFopen ?></td>
                                <td>必须开启</td>
                                <td>
                                    <?php if ($allowUrlFopenState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>

                                </td>
                            </tr>
                            <tr>
                                <td>allow_url_include</td>
                                <td><?= $allowUrlInclude ?></td>
                                <td>必须关闭</td>
                                <td>
                                    <?php if ($allowUrlIncludeState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>

                                </td>
                            </tr>

                            <tr>
                                <td>short_open_tag</td>
                                <td>
                                    <?= $shortOpenTag ?>
                                </td>
                                <td>必须关闭</td>
                                <td>
                                    <?php if ($shortOpenTagState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>default_charset</td>
                                <td>
                                    <?= $defaultCharSet ?>
                                </td>
                                <td>必须UTF-8</td>
                                <td>
                                    <?php if ($defaultCharSetState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>mbstring 扩展</td>
                                <td><?= $mbstringExt ?></td>
                                <td>必须开启</td>
                                <td>
                                    <?php if ($mbstringExtState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>curl 扩展</td>
                                <td><?= $curlExt ?></td>
                                <td>必须开启</td>
                                <td>
                                    <?php if ($curlExtState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>gd 扩展</td>
                                <td><?= $gdExt ?>（支持 png jpg gif）</td>
                                <td>必须开启</td>
                                <td>
                                    <?php if ($gdExtState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>pdo 扩展</td>
                                <td>
                                    <?= $pdoExtState ?>
                                </td>
                                <td>建议开启</td>
                                <td>
                                    <?php if (empty($pdo)) { ?>
                                        <b class="error">×</b>
                                    <?php } else { ?>
                                        <b class="correct">√</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>mysqli 扩展</td>
                                <td><?= $mysqliExt ?></td>
                                <td>建议开启</td>
                                <td>
                                    <span>
                                       <?php if ($mysqli) { ?>
                                           <b class="correct">√</b>
                                       <?php } else { ?>
                                           <b class="error">×</b>
                                       <?php } ?>
                                    </span>
                                </td>
                            </tr>

                            <tr>
                                <td>redis扩展</td>
                                <td>
                                    <?= $redisExt ?>
                                </td>
                                <td>建议开启</td>
                                <td>
                                    <?php if ($redisExtState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>soap 扩展</td>
                                <td>
                                    <?= $soapExt ?>
                                </td>
                                <td>建议开启</td>
                                <td>
                                    <?php if ($soapExtState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else {?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>xml 扩展</td>
                                <td>
                                    <?= $xmlExt ?>
                                </td>
                                <td>建议开启</td>
                                <td>
                                    <?php if ($xmlExtState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else {?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>openssl扩展</td>
                                <td>
                                    <?= $openssl ?>
                                </td>
                                <td>建议开启</td>
                                <td>
                                    <?php if ($opensslState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>apcu 扩展</td>
                                <td>
                                    <?= $apcuExt ?>
                                </td>
                                <td><b style="color: #9F9F5F;">此项扩展在Linux下开启</b></td>
                                <td>
                                    <?php if ($apcuExtState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error" style="color: #9F9F5F;">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>json 扩展</td>
                                <td>
                                    <?= $jsonExt ?>
                                </td>
                                <td><b style="color: #9F9F5F;">此项扩展可选开启</b></td>
                                <td>
                                    <?php if ($jsonExtState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error" style="color: #9F9F5F;">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>oci 扩展</td>
                                <td>
                                    <?= $ociExt ?>
                                </td>
                                <td><b style="color: #9F9F5F;">此项扩展可选开启</b></td>
                                <td>
                                    <?php if ($ociExtState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error" style="color: #9F9F5F;">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <tr>
                                <td>hprose 扩展</td>
                                <td>
                                    <?= $hproseExt ?>
                                </td>
                                <td><b style="color: #9F9F5F;">此项扩展可选开启</b></td>
                                <td>
                                    <?php if ($hproseState) { ?>
                                        <b class="correct">√</b>
                                    <?php } else { ?>
                                        <b class="error" style="color: #9F9F5F;">×</b>
                                    <?php } ?>
                                </td>
                            </tr>

                            <form action="" method="post">
                                <tr>
                                    <td>MYSQL数据库连接检测</td>
                                    <td>地址<input type="text" name="host" value="localhost"/><br/>端口<input type="text" name="port" value="3306"/>
                                    </td>
                                    <td>账号<input type="text" name="username" size="10"/><br/>密码<input type="password" name="password" size="10"/></td>
                                    <td><input type="submit" name="act" value="MySQL检测"/></td>
                                </tr>
                                <tr>
                                    <td>MySQL 版本</td>
                                    <td>
                                        <?= $mysqlVersion ?>
                                    </td>
                                    <td>版本5.5 或者5.7及以上</td>
                                    <td>
                                        <?php if (!$hasTested) { ?>
                                            <b class="error">未检测，请点击检测</b>
                                        <?php } else if (empty($mysqlState)) { ?>
                                            <b class="error">×</b>
                                        <?php } else { ?>
                                            <b class="correct">√</b>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>函数检测</td>
                                    <td>
                                        请输入您要检测的函数：
                                    </td>
                                    <td><input type="text" name="funName" size="20"/></td>
                                    <td>
                                        <input class="btn" type="submit" name="act" align="right" value="函数检测"/>
                                    </td>
                                </tr>
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>