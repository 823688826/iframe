<?php
/**
 * Created by PhpStorm.
 * User: Abel
 * Date: 2017/9/24
 * Time: 8:35
 */
class UploadFileLibrary{

    private $error = '';
    public $ext = [];//扩展
    public $uploadPath;//上传路径
    private $md5Hash = [];//文件hash
    private $uploadFile;
    private $rootFile;
    public $rootDir;
    private $ci;

    public function __construct($data = array())
    {
        $this->ci = get_instance();
    }

    public function uploadFile($files)
    {
        if(!$files) {
            $this->error = '请选择要上传的文件';
            return false;
        }


        if(!$this->uploadPath) {
            $this->error = '请选择上传文件的根目录';
            return false;
        }

        if(isset($files['error']) && $files['error'] != 0)
        {
            switch ($files['error'])
            {
                case 1:
                    $this->error = '上传文件过大';
                    break;
                case 2:
                    $this->error = '传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
                    break;
                case 3:
                    $this->error = '文件只有部分被上传';
                    break;
                case 4:
                    $this->error = '没有文件被上传';
                    break;
                case 6:
                    $this->error = '找不到临时文件夹';
                    break;
                case 7:
                    $this->error = '文件写入失败';
                    break;
                default:
                    $this->error = '未知错误';
                    break;
            }
            return false;
        }

        if(!$files['name'])
        {
            $this->error = '上传文件名称不能为空';
            return false;
        }

        /* 获取文件hash */
        $this->md5Hash['md5'] = md5_file($files['tmp_name']);
        $this->md5Hash['hash'] = sha1_file($files['tmp_name']);

        $file_name_type = strtolower(pathinfo($files['name'],PATHINFO_EXTENSION));

        $tmp_type = strtolower(pathinfo($files['name'],PATHINFO_EXTENSION));

        if($file_name_type != $tmp_type)
        {
            $this->error = '临时文件类型和上传文件类型不一致,非法上传';
            return false;
        }

        /*检查mime类型和临时缓存文件上传类型*/
        if(!in_array($file_name_type,$this->ext) || !in_array($tmp_type,$this->ext))
        {
            $this->error = '上传文件类型不允许';
            return false;
        }

        if(!is_uploaded_file($files['tmp_name']))//是否用post传来的
        {
            $this->error = '非法上传文件';
            return false;
        }
        if(!is_dir($this->uploadPath))
        {
            $this->error = '上传文件路径设置错误';
            return false;
        }
        $rand_name = uuid();
        /*进行文件上传*/
        $result = move_uploaded_file($files['tmp_name'],$this->uploadPath.$rand_name.'.'.$tmp_type);
        if($result){
            $this->uploadFile = $this->uploadPath.$rand_name.'.'.$tmp_type;
            $this->rootFile = $this->rootDir.$rand_name.'.'.$tmp_type;
            return true;
        }else{
            $this->error = '文件上传失败';
            return false;
        }

    }


    //上传压缩文件
    public function uploadImage($files,$width,$height)
    {

        if(!empty($files) && !empty($width) && !empty($height)){
            $old_path = $this->uploadPath;
            if(!$this-> uploadFile($files))
            {
                //上传失败
                return false;
            } else {
                //上传成功
                $file_name_type = strtolower(pathinfo($files['name'],PATHINFO_EXTENSION));
                $orig_name = strtolower(pathinfo($files['name'],PATHINFO_FILENAME));
                //重新命名
                $new_name = md5($orig_name.strtoupper(md5(uniqid())));
                $config_img['image_library'] = 'gd2';
                $config_img['source_image'] = $this->getUploadFile();
                $config_img['maintain_ratio'] = false;
                $config_img['width'] = $width;
                $config_img['height'] = $height;
                $config_img['new_image'] = $this->uploadPath.$new_name.".".$file_name_type;
                $config_img['master_dim'] = 'auto';

                $this->ci->load->loadSysLibrary('Image');
                $this->ci->Image->initialize($config_img);

                //裁剪成功
                if ($this->ci->Image->resize()){
                    $this->ci->Image->clear();
                    //删除tmp下的原图
                    unlink($this->getUploadFile());

                    $this->rootFile = $this->rootDir.$new_name.".".$file_name_type;
                    $this->uploadFile = $old_path.$new_name.".".$file_name_type;
                    return true;
                }
            }
        }
        return false;
    }
    public function getErrorInfo(){
        return $this->error;
    }

    public function getFileMd5Hash()
    {
        return $this->md5Hash;
    }

    public function getUploadFile()
    {
        return $this->uploadFile;
    }

    public function getWebFile()
    {
        return $this->rootFile;
    }
}