<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 */
namespace User\Controller;

use Common\Controller\BaseController;
use Think\Log;

class JokeController extends BaseController
{
    public function index()
    {
        $this->_empty();
    }

    /**
     * 发布笑话
     */
    public function publish()
    {
        $this->checkLogin();//使用该功能前应先登录
        if (IS_AJAX) {
            $Joke = D('JokePublish');
            if ($Joke->create()) {
                $Joke->publish($this->user['id']);
                D('User')->addAttribute($this->user['id'],'send_num',1);
                $this->ajaxReturn(array('err' => 1, 'msg' => '发布成功，请等待审核！'));
            } else {
                $this->ajaxReturn(array('err' => 1, 'msg' => $Joke->getError()));
            }
        }
        $this->assign('allow', 1);
        $this->assign('title', '发布笑话');
        $this->display();
    }

    /**
     * 上传文件
     */
    public function uploadFile()
    {
        $targetFolder = 'joke/';
        if (!empty($_FILES)) {
            $url = upload_file($targetFolder);
            $this->ajaxReturn(array('status' => 1, 'info' => '上传成功', 'url' => $url, 'm_url' => $url['m_image']));
        }
    }
}