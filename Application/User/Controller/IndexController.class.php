<?php
namespace User\Controller;

use User\Model\User;
use Common\Controller\BaseController;
use Common\Model\JokeModel;
use Think\Page;
use Common\Model\TraceModel;

class IndexController extends BaseController
{
    public function test()
    {
        $this->display();
    }

    public function index()
    {
        $this->_empty();
    }

    /**
     * 用户登录
     */
    public function login()
    {
        if (IS_AJAX) {
            $User = D('UserLogin');
            if ($User->create()) {
                $User->login();
                $this->ajaxReturn(array('err' => 1, 'msg' => '登录成功!'));
            } else {
                $this->ajaxReturn(['err' => 0, 'msg' => $User->getError()]);
            }
        } else {
            $this->assign('title', '用户登录');
            $this->display();
        }
    }

    /**
     * 用户注册
     */
    public function register()
    {
        if (IS_AJAX) {
            if (I('password') != I('confirm_password'))
                $this->ajaxReturn(['err' => 0, 'msg' => '两次输入密码不一致']);
            $User = D('UserRegister');
            if ($User->create()) {
                if (!check_verify(I('code')))
                    $this->ajaxReturn(['err' => 0, 'msg' => '验证码错误']);
                $User->register();
                $this->ajaxReturn(['err' => 1, 'msg' => U('User/Index/login')]);
            } else {
                $this->ajaxReturn(['err' => 0, 'msg' => $User->getError()[0]]);
            }
        } else {
            $this->assign('title', '用户注册');
            $this->display();
        }
    }

    /**
     * 检测邮箱是不是已经注册
     */
    public function checkEmail()
    {
        if (M('User')->where(['email' => I('email')])->count()) {
            $this->ajaxReturn(array('err' => 0, 'msg' => '邮箱已存在!'));
        } else {
            $this->ajaxReturn(array('err' => 1, 'msg' => '可以注册!'));
        }
    }

    /**
     * 检测用户名是不是已经注册
     */
    public function checkUsername()
    {
        if (M('User')->where(['username' => I('username')])->count()) {
            $this->ajaxReturn(array('err' => 0, 'msg' => '昵称已存在!'));
        } else {
            $this->ajaxReturn(array('err' => 1, 'msg' => '可以注册!'));
        }
    }

    /**
     * 退出操作
     */
    public function logout()
    {
        //清除session
        unset($_SESSION['id']);
        unset($_SESSION['expire']);
        unset($_SESSION['user']);
        //清除cookie
        cookie('user[id]', null);
        cookie('user[username]', null);
        cookie('user[login_time]', null);
        cookie('user[key]', null);
        $this->redirect(U('/'));
    }

    public function jokes()
    {
        $User = D('User');
        $user = $User->find(I('id'));
        $Joke = D('Joke');
        $count = $Joke->where(['status' => JokeModel::JOKE_STATUS_VALID])->count();
        $page = new Page($count, C('PAGE_SIZE'));
        $jokes = $Joke->relation(true)->where(['status' => JokeModel::JOKE_STATUS_VALID, 'user_id' => I('id')])
            ->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('user_joke', $jokes);
        $this->assign('isIndex', true);
        $this->assign('title', $user['username'] . '用户主页');
        $this->assign('keywords', $user['username'] . '用户主页');
        $this->assign('description', $user['username'] . '用户主页');
        $this->assign('user_info', $user);
        $this->display();
    }

    /**
     * 签到操作
     */
    public function sign()
    {
        if (!$this->user) {
            $this->ajaxReturn(['err' => 0, 'msg' => '请先登录!']);
        }
        if (date('Y-m-d', time()) > date('Y-m-d', $this->user['sign_time'])) {
            $User=D('User');
            $User->addAttribute($this->user['id'],'money',10);
            $User->where(['id'=>$this->user['id']])->save(['sign_time'=>time()]);
//            D('Trace')->insert($this->user['id'],30,'签到获得30金币',TraceModel::TRACE_TYPE_IN);
            D('Trace')->insert($this->user['id'],30,'签到获得30金币',TraceModel::TRACE_TYPE_IN);
            $this->ajaxReturn(['err' => 1, 'msg' => '恭喜你签到成功!']);
        } else {
            $this->ajaxReturn(['err' => 0, 'msg' => '今天已经签过到了!!']);
        }
    }
}