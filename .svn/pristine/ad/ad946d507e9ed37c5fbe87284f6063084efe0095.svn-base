<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-4-2 0002
 * Time: 19:31
 */

/**
 * @description 抛出异常
 * @param $msg
 * @throws Exception
 */
function productError($msg)
{
    throw new Exception($msg."\r\n");
}

/**
 * @description 生成uuid
 * @param $msg
 * @throws Exception
 */
function uuid()
{
    return strtoupper(md5(uniqid()));
}


/**
 * @description 获取配置信息
 * @param $item
 * @return mixed|null
 */
function config($item)
{
    static $config;
    if(!isset($config[$item]))
    {
        $get_config = include_once $_SERVER['DOCUMENT_ROOT']."/App/Config/{$item}.php";
        if($get_config) {
            $config[$item] = $get_config;
            return $get_config;
        }else{
            include_once $_SERVER['DOCUMENT_ROOT']."/App/Config/{$item}.php";
            return NULL;
        }
    }else{
        return $config[$item];
    }
}

function get_instance()
{
    return FactoryController::get_instance();
}

function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit();
}
//判断是否最新
function is_new($time)
{
    $now = date('Ymd');
    //判断格式
    if(is_numeric($time)){
        //时间戳
        if(date('Ymd', $time) >= $now)
        {
            return true;
        }
    } else {
        //非时间戳
        if(date('Ymd', strtotime($time)) >= $now)
        {
            return true;
        }
    }

    return false;
}
//时间差
function timediff( $begin_time, $end_time )
{
    if( $begin_time < $end_time ) {
        $starttime = $begin_time;
        $endtime = $end_time;
    } else {
        $starttime = $end_time;
        $endtime = $begin_time;
    }
    $timediff = $endtime - $starttime;
    $days = intval( $timediff / 86400 );
    $remain = $timediff % 86400;
    $hours = intval( $remain / 3600 );
    $remain = $remain % 3600;
    $mins = intval( $remain / 60 );
    $secs = $remain % 60;
    $res = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
    return $res;
}
//创建xml
function create_xml($ar, $xml) {
    foreach($ar as $k=>$v) {
        if(is_array($v)) {
            $x = $xml->addChild($k);
            create_xml($v, $x);
        }else $xml->addChild($k, $v);
    }
}

if ( ! function_exists('remove_invisible_characters'))
{
    /**
     * Remove Invisible Characters
     *
     * This prevents sandwiching null characters
     * between ascii characters, like Java\0script.
     *
     * @param	string
     * @param	bool
     * @return	string
     */
    function remove_invisible_characters($str, $url_encoded = TRUE)
    {
        $non_displayables = array();

        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($url_encoded)
        {
            $non_displayables[] = '/%0[0-8bcef]/i';	// url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/i';	// url encoded 16-31
            $non_displayables[] = '/%7f/i';	// url encoded 127
        }

        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

        do
        {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        }
        while ($count);

        return $str;
    }
}
/**
 * 接口输出
 * @param array $data
 * @param int $type 0 json 1 xml
 */
function api_echo($data = [], $type = 0)
{
    if($type <= 0) {
        //json
        //header('content-type:application/json;charset=utf8');
        echo json_encode($data);
        return;
    }

    //xml
    header("Content-type:text/xml");
    $xml = simplexml_load_string('<request />');
    create_xml($data, $xml);
    echo $xml -> saveXML();
}
//csrf
function csrf_hidden($input_id = 'flycorn_token'){
    $ci = &get_instance();
    $name = $ci->security->get_csrf_token_name();
    $val = $ci->security->get_csrf_hash();
    echo "<input type=\"hidden\" class=\"flycorn_token\" id=\"$input_id\" name=\"$name\" value=\"$val\" />";
}
//获取GET的数据
function form_get()
{
    $data = array();
    if(empty($_GET)){
        return $data;
    }

    foreach($_GET as $k => $v)
    {
        $data[$k] = trim($v, ' ');
    }

    return $data;
}

//获取POST的数据
function form_post()
{
    $data = array();
    if(empty($_POST)){
        return $data;
    }

    foreach($_POST as $k => $v)
    {
        $data[$k] = trim($v, ' ');
    }

    return $data;
}

//随机字符串
function randomstr($length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $str = '';
    for ( $i = 0; $i < $length; $i++ )
    {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
        $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    return $str;
}

//提示信息
function show_msg($title, $content, $url, $time = 8)
{
    $ci = &get_instance();
    $message = array(
        'title' => $title,
        'content' => $content,
        'url'   => $url,
        'time'  => $time
    );
    include(APPPATH.'views/admin/Message.php');
    exit();
}
//获取内容中所有的图片
function getImgs($content,$withimgpx=0, $order = 'ALL', $flag = false)
{
    $pattern = "/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
    //$pattern = "/<img.+src=\'?(.+\.(jpg|gif|bmp|bnp|png))\'?.+>/i";
    if(!$flag){
        preg_match_all ( $pattern, $content, $match );
    } else {
        preg_match ( $pattern, $content, $match );
    }
    if (isset ( $match [1] ) && ! empty ( $match [1] )) {
        if ($order === 'ALL') {
            if($withimgpx)
            {
                $img = $match[1];
                foreach($img as $id=>$im)
                {
                    $org_info = @getimagesize ( $im);
                    $img[$id] = $im.'|'.$org_info[0].'x'.$org_info[1];
                }

                return $img;
            }else{
                return $match [1];
            }

        }
        if (is_numeric ( $order ) && isset ( $match [1] [$order] )) {
            if($withimgpx)
            {
                $org_info = @getimagesize ( $match [1][$order]);
                return $match [1][$order].'|'.$org_info[0].'x'.$org_info[1];

            }else{
                return $match [1] [$order];
            }

        }
    }
    return '';
}
//获取文本中所有图片的属性值
function getImgsData($text)
{
    $img_list = [];
    $pattern = "/<img\s.*?>/";
    $c1 = preg_match_all($pattern, $text, $m1);  //先取出所有img标签文本
    for($i=0; $i<$c1; $i++) {    //对所有的img标签进行取属性
        $c2 = preg_match_all('/(\w+)\s*=\s*(?:(?:(["\'])(.*?)(?=\2))|([^\/\s]*))/', $m1[0][$i], $m2);   //匹配出所有的属性
        for($j=0; $j<$c2; $j++) {    //将匹配完的结果进行结构重组
            $img_list[$i][$m2[1][$j]] = !empty($m2[4][$j]) ? $m2[4][$j] : $m2[3][$j];
        }
    }
    return $img_list;
}

//替换所有图片
function replaceImgs($str)
{
    preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU",$str,$matches);
    for($i=0,$j=count($matches[0]);$i<$j;$i++){
        $str = str_replace($matches[0][$i],"@img@",$str);
    }

    return $str;
}
//图片替换
function imgReplace($text = '', $new_imgs = [], $field_src = '')
{
    preg_match_all("/@img@/isU",$text, $matches);
    for($i=0,$j=count($matches[0]);$i<$j;$i++){
        $img_html = '<img src="'.$new_imgs[$i].'" ';
        if(!empty($field_src)) $img_html .= ' data-src="'.$new_imgs[$i].'" ';
        $img_html .= ' />';
        $text = preg_replace("/@img@/isU", $img_html, $text, 1);
    }
    return $text;
}
//文件管理方法
//目录大小
function dir_size($dir) {
    $handle=opendir($dir);
    $size = 0;
    while ( $file=readdir($handle) ) {
        if ( ( $file == "." ) || ( $file == ".." ) ) continue;
        if ( is_dir("$dir/$file") ) $size += dir_size("$dir/$file");
        else $size += @filesize("$dir/$file");
    }
    closedir($handle);
    return $size;
}
//文件大小
function file_size($f='') {
    return @filesize($f);
}
//文件大小转换
function format_size($bytes){
    if($bytes >= 1073741824){
        $bytes = round($bytes / 1073741824 * 100) / 100 . ' GB';
    }elseif($bytes >= 1048576){
        $bytes = round($bytes / 1048576 * 100) / 100 . ' MB';
    }elseif($bytes >= 1024){
        $bytes = round($bytes / 1024 * 100) / 100 . ' KB';
    }else{
        $bytes = $bytes . ' B';
    }
    return $bytes;
}
//文件权限
function file_perm($f='') {
    return @substr(sprintf('%o', fileperms($f)), -4);
}
//文件创建时间
function file_ctime($f='') {
    return @date("Y-m-d H:i:s",filectime($f));
}
//文件修改时间
function file_mtime($f='') {
    return @date("Y-m-d H:i:s",filemtime($f));
}
//删除文件夹下的所有文件
function remove_dir($path){
    //判断是否是文件夹
    if(is_dir($path)){
        //判断是否打开成功
        if($handle = opendir($path)){
            //读取文件
            while($file = readdir($handle)){
                //判断是否是文件夹
                if(is_dir($path.'/'.$file.'/') && $file!='.' && $file!='..'){
                    remove_dir($path.'/'.$file.'/');
                } else {
                    if($file!='.' && $file!='..'){
                        //删除文件
                        @unlink($path.'/'.$file);
                    }
                }

            }
            //关闭文件夹
            closedir($handle);
            //删除文件夹
            @rmdir($path.'/'.$file.'/');
        }
    }
}
//检测目录
function check_dir($dir_name){
    if(!empty($dir_name) && $dir_name != '.'){
        $dir_arr = array();
        $dir_arr = explode('/',$dir_name);
        //遍历
        $tmp_dir = '';
        foreach($dir_arr as $k => $v){
            $tmp_dir .= $v.'/';

            //判断文件夹是否存在
            if(!file_exists($tmp_dir)){
                @mkdir($tmp_dir); //创建文件夹
            }
        }
    }
}
//修改目录权限
function chmod_dir($path, $filemode) {
    if (!is_dir($path))
        return chmod($path, $filemode);
    $dh = opendir($path);
    while (($file = readdir($dh)) !== false) {
        if($file != '.' && $file != '..') {
            $fullpath = $path.'/'.$file;
            if(is_link($fullpath))
                return FALSE;
            elseif(!is_dir($fullpath) && !chmod($fullpath, $filemode))
                return FALSE;
            elseif(!chmod_dir($fullpath, $filemode))
                return FALSE;
        }
    }
    closedir($dh);
    if(chmod($path, $filemode))
        return TRUE;
    else
        return FALSE;
}
//读取文件
function read_file($file)
{
    if ( ! file_exists($file))
    {
        return FALSE;
    }

    if (function_exists('file_get_contents'))
    {
        return file_get_contents($file);
    }

    if ( ! $fp = @fopen($file, FOPEN_READ))
    {
        return FALSE;
    }

    flock($fp, LOCK_SH);

    $data = '';
    if (filesize($file) > 0)
    {
        $data =& fread($fp, filesize($file));
    }

    flock($fp, LOCK_UN);
    fclose($fp);

    return $data;
}
/**
 * 验证是否是图片
 * @param $filename
 * @return bool|int
 */
function is_image($filename){
    $types = '.gif|.jpg|.jpeg|.png|.bmp';//定义检查的图片类型
    if(file_exists($filename)){
        $info = getimagesize($filename);
        $ext = image_type_to_extension($info['2']);
        return stripos($types, strtolower($ext));
    }else{
        return false;
    }
}
/**
 * 遍历获取目录下的指定类型的文件
 * @param $path
 * @param array $files
 * @return array
 */
function getfiles($path, $allowFiles, &$files = array())
{
    if (!is_dir($path)) return null;
    if(substr($path, strlen($path) - 1) != '/') $path .= '/';
    $handle = opendir($path);
    while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..') {
            $path2 = $path . $file;
            if (is_dir($path2)) {
                getfiles($path2, $allowFiles, $files);
            } else {
                if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                    $files[] = array(
                        'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                        'mtime'=> filemtime($path2)
                    );
                }
            }
        }
    }
    return $files;
}
//重写site_url方法
function site_url($uri = '', $protocol = NULL)
{
    $url = get_instance()->config->site_url($uri, $protocol);

    if(strpos($uri, 'api') === 0 || strpos($uri, 'admin') === 0) return $url;

    $url_info = parse_url($url);

    //加密
    $key = 'flycorn&piyao';
    //$str = authcode($url, 'ENCODE', $key);
    $str = encrypt($url, 'E', $key);

    $str = str_replace('//', '2/2', $str);

    return $url_info['scheme'].'://'.$url_info['host'].'/py_'.$str;
}
//获取解密的url数据
function getDecryptUrl($url = '')
{
    $data = [];

    $url = str_replace('.|', '+', $url);
    $url = str_replace('2/2', '//', $url);

    //解密
    $key = 'flycorn&piyao';
    //$url = authcode($url,'DECODE',$key);
    $url = encrypt($url, 'D', $key);

    $url_info = parse_url($url);

    $tmp = explode('/', str_replace('.html','',trim($url_info['path'], '/')));

    $data['_url'] = $url;
    $data['_url_info'] = $url_info;
    $data['_data'] = $tmp;
    $data['_id'] = array_pop($tmp);

    //判断是否有传参
    if(isset($url_info['query'])){
        $query_arr = explode('&', trim($url_info['query'], ' '));
        if(!empty($query_arr)){
            foreach($query_arr as $item){
                $foo = explode('=', $item);
                if(!empty($foo)){
                    $data[@$foo[0]] = @$foo[1];
                }
            }
        }
    }
    return $data;
}
/**
 * Description: openssl加密
 * @param $data
 * @return bool|string
 */
function encrypt($data,$appKey = 'UwYPxN@c&^Rayf%Lff&BL&aR6L&P6EEBsD8weTrS61vBGLw#83BmCuki70F0uOyp')
{
    if (!$data) {
        return false;
    }
    $encrypt = [];
    $key = strtoupper(md5(base64_encode(json_encode($appKey))));//固定形成的加密钥匙为了防止别人容易破解采用md5混淆,与解密处必须对应
    $encrypt['data'] = json_encode($data);//要加密的数据
    $encrypt['iv'] = (substr(md5(base64_encode(json_encode($appKey))), 0, 16));//另一处对应的值,与解密处必须对应
    $encrypt['value'] = (openssl_encrypt($encrypt['data'], 'AES-128-CBC', $key, 0, $encrypt['iv']));
    return base64_encode(json_encode($encrypt));
}

/**
 * Description: openssl解密
 * @param $encrypt
 * @return bool|mixed
 */
function decrypt($encrypt,$appKey = 'UwYPxN@c&^Rayf%Lff&BL&aR6L&P6EEBsD8weTrS61vBGLw#83BmCuki70F0uOyp')
{
    if (!$encrypt) {
        return false;
    }
    $key = strtoupper(md5(base64_encode(json_encode($appKey))));//固定形成的加密钥匙为了防止别人容易破解采用md5混淆,与加密处必须对应
    $encrypt_arr = json_decode(base64_decode($encrypt), 1);
    $decrypt = openssl_decrypt(($encrypt_arr['value']), 'AES-128-CBC', $key, 0, trim($encrypt_arr['iv']));
    return json_decode($decrypt);
}



if ( ! function_exists('is_php'))
{
    /**
     * Determines if the current version of PHP is equal to or greater than the supplied value
     *
     * @param	string
     * @return	bool	TRUE if the current version is $version or higher
     */
    function is_php($version)
    {
        static $_is_php;
        $version = (string) $version;

        if ( ! isset($_is_php[$version]))
        {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}

function easySession($key,$defaultValue = "")
{
    return isset($_SESSION[$key]) ? $_SESSION[$key] : $defaultValue;
}

function easyCookie($key,$defaultValue = "")
{
    return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $defaultValue;
}

/**
 * Get random bytes
 *
 * @param	int	$length	Output length
 * @return	string
 */
function get_random_bytes($length)
{
    if (empty($length) OR ! ctype_digit((string) $length))
    {
        return FALSE;
    }

    if (function_exists('random_bytes'))
    {
        try
        {
            // The cast is required to avoid TypeError
            return random_bytes((int) $length);
        }
        catch (Exception $e)
        {
            // If random_bytes() can't do the job, we can't either ...
            // There's no point in using fallbacks.
            productError($e->getMessage());
            return FALSE;
        }
    }

    // Unfortunately, none of the following PRNGs is guaranteed to exist ...
    if (defined('MCRYPT_DEV_URANDOM') && ($output = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM)) !== FALSE)
    {
        return $output;
    }


    if (is_readable('/dev/urandom') && ($fp = fopen('/dev/urandom', 'rb')) !== FALSE)
    {
        // Try not to waste entropy ...
        is_php('5.4') && stream_set_chunk_size($fp, $length);
        $output = fread($fp, $length);
        fclose($fp);
        if ($output !== FALSE)
        {
            return $output;
        }
    }

    if (function_exists('openssl_random_pseudo_bytes'))
    {
        return openssl_random_pseudo_bytes($length);
    }

    return FALSE;
}

//基础url
function base_url($resource_url)
{
    $base_url = isset(config("Config")["base_url"]) ? config("Config")["base_url"] : "";
    return $base_url.$resource_url;
}

//资源url
function resource_url($resource_url)
{
    $base_url = isset(config("Config")["base_url"]) ? config("Config")["base_url"] : "";
    return $base_url.$resource_url;
}

//负责翻译的url
function translationText($chinese,$english)
{
    $en = intval(get_instance()->input->get("EN"));
    return $en == 0 ? $chinese : $english;
}
