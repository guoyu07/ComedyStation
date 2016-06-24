<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 */
use Think\Upload;
use Think\Image;
/**
 * 验证验证码是否正确
 * @param $code
 * @param int $id
 * @return bool
 */
function check_verify($code)
{
    $verify = new \Think\Verify();
    return $verify->check($code);
}

/**
 * 加密密码
 * @param $str
 * @param string $key
 * @return string
 */
function password_md5($str, $key = 'comdy')
{
    return '' === $str ? '' : md5(sha1($str) . $key);
}


/**
 * 分割图片
 * @param $image
 * @param $x
 * @param $y
 * @param $w
 * @param $h
 * @return string
 */
function cut_img($image, $x, $y, $w, $h)
{
    $path = pathinfo($image);
    $type = $path['extension'];
    switch ($type) {
        case 'jpg' :
            $image = imagecreatefromjpeg($image);
            break;
        case 'jpeg' :
            $image = imagecreatefromjpeg($image);
            break;
        case 'png' :
            $image = imagecreatefrompng($image);
            break;
        case 'gif' :
            $image = imagecreatefromgif($image);
            break;
        default:
            $this->ajaxReturn(array('err' => 0, 'msg' => '不支持的格式!'));
            exit;
    }
    $copy = image_crop($image, $x, $y, $w, $h);

    $filename = $path['basename'];
    $cutPicfolder = $path['dirname'] . '/';

    $newName = 'cut_' . $filename;
    $targetPic = $cutPicfolder . $newName;

    if (false === imagejpeg($copy, $targetPic)) {
        $this->ajaxReturn(array('err' => 0, 'msg' => '生成裁剪图片失败！请确认保存路径存在且可写！'));
    }
    @unlink($image);
    return $targetPic;
}

/**
 * 剪裁图片
 * @param $image
 * @param $x
 * @param $y
 * @param $w
 * @param $h
 * @return bool|resource
 */
function image_crop($image, $x, $y, $w, $h)
{
    $tw = imagesx($image);
    $th = imagesy($image);

    if ($x > $tw || $y > $th || $w > $tw || $h > $th)
        return FALSE;
    $temp = imagecreatetruecolor($w, $h);
    imagecopyresampled($temp, $image, 0, 0, $x, $y, $w, $h, $w, $h);
    return $temp;

}

/**
 * 上传文件
 * @param $savePath
 * @param bool $is_thumb
 * @return bool|string
 */
function upload_file($savePath, $is_thumb = true)
{
    $fileParts = pathinfo($_FILES['Filedata']['name']);
    $extension = $fileParts['extension'];
    //导入上传类
    $upload = new Upload();
    //设置上传文件大小
    $upload->maxSize = 5120 * 5120;
    //设置上传文件类型
    $upload->exts = explode(',', 'jpg,gif,png,jpeg');
    //设置附件上传目录
    $upload->savePath = $savePath;

    //设置上传文件规则
    $upload->saveName = uniqid;
    if(!is_dir($savePath))
    {
        mkdir($savePath);
    }
    if (!($info=$upload->upload())) {
        //捕获上传异常
        return false;
    }
    return $upload->rootPath.$info['Filedata']['savepath'].$info['Filedata']['savename'];
}


