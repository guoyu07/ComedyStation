<?php

/**
 * Created by PhpStorm.
 * User: noble4cc
 */
namespace Common\Controller;

use Think\Controller;
use Common\Model\JokeModel;
class BaseController extends Controller
{
    protected $user = false;
    protected $flink;
    protected $tags;

    public function _initialize()
    {
        $this->getUser();
        $this->get_flink();
    }

    public function _empty()
    {
        header('HTTP/1.1 404 Not Found');
        $this->display('Common@:404');
    }

    /**
     * 用户登陆处理
     */
    private function getUser()
    {
        if (isset($_SESSION['id']) && isset($_SESSION['expire'])) {
            if ($_SESSION['cs_expire'] < time()) {
                unset($_SESSION['id']);
                unset($_SESSION['expire']);
                unset($_SESSION['user']);
            } else {
                $this->user = $_SESSION['user'];
                $_SESSION['expire']=time()+C('SESSION_EXPIRE');
            }
        } else {
            if($_COOKIE['user']['id'])
            {
                $id=$_COOKIE['user']['id'];
                $key=$_COOKIE['user']['key'];
                $User=M('User');
                $user=$User->find($id);
                if(is_array($user)&&md5($user['password']===$key))
                {
                    $this->user=$user;
                    $_SESSION['start_time'] = time();
                }
            }
        }
        if($this->user)
        {
            unset($this->user['password']);
            $this->assign('user',$this->user);
        }
    }

    /**
     * 登陆检测
     */
    public function checkLogin()
    {
        if(!$this->user) {
            if(IS_AJAX) {
                $this->ajaxReturn(array('err' => 0,'msg' => '请先登录!'));
            }
            redirect(U('/User/index/login/'));
        }
    }

    /**
     * 获得推荐笑话
     */
    public function getRecommend()
    {
        $Joke=D('Joke');
        $pic=$Joke->getRecommend(JokeModel::JOKE_TYPE_PIC);
        $text=$Joke->getRecommend(JokeModel::JOKE_TYPE_TEXT);
        $this->assign('pic',$pic);
        $this->assign('text',$text);
    }

    /**
     * 获得标签
     */
    public function getTags()
    {
        if(S('tags')) {
            $tags = S('tags');
        }else {
            $Tag = D('tags');
            $tags = $Tag->order('sort asc')->select();
            S('tags',$tags,'3600');
        }
        $this->tags = $tags;
        $this->assign('tags',$this->tags);
    }

    /**
     * 获得友情链接
     */
    private function get_flink() {
        if(S('flink')) {
            $flink = S('flink');
        }else {
            $flink_mod = D('flink');
            $flink = $flink_mod->order('ordid asc')->where('status=1')->select();
            S('flink',$flink,'3600');
        }
        $this->flink = $flink;
        $this->assign('flink',$this->flink);
    }
}