<?php
namespace Home\Controller;

use Common\Controller\BaseController;
use Common\Model\JokeModel;
use Think\Page;

class IndexController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->getRecommend();
        $this->getTags();
    }

    /**
     * 笑话列表
     */
    public function index()
    {
        $Joke = D('Joke');
        $count = $Joke->where(['status' => JokeModel::JOKE_STATUS_VALID])->count();
        $page = new Page($count, C('PAGE_SIZE'), ['p' => I('p')]);
        $jokes = $Joke->relation(true)->where(['status' => JokeModel::JOKE_STATUS_VALID])
            ->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        $page_str = str_replace('/Home/Index/index/', '/jokes/', $page->show());
        $this->assign('page', $page_str);
        $this->assign('user_joke', $jokes);
        $this->assign('title','爆笑驿站--只为你的笑脸');
        $this->display('index');
    }

    /**
     * 笑话详情
     */
    public function joke()
    {
        $Joke = D('Joke');
        $Comment = D('Comment');
        $joke = $Joke->findValid(I('id'));
        $pre = $Joke->findPreValid(I('id'));
        $next = $Joke->findNextValid(I('id'));
        $comments = $Comment->findList();
        $this->assign('user_joke', $joke);
        $this->assign('review', $comments);
        $this->assign('rel_joke', ['pre_joke_id' => $pre ? $pre['id'] : '', 'next_joke_id' => $next ? $next['id'] : '']);
        $this->display();
    }


    /**
     * 热门笑话,分时间
     */
    public function hot()
    {
        $Joke = D('Joke');
        $type = '';
        if (I('type') == 'week') {
            $time = time() - 7 * 24 * 3600;
            $type = 'week';
        } else if (I('type') == 'month') {
            $time = time() - 30 * 24 * 3600;
            $type = 'month';
        } else {
            $time = time() - 8 * 3600;
            $type = 'hour';
        }
        $where = ["status=" . JokeModel::JOKE_STATUS_VALID . " and created_time>={$time}"];
        $count = $Joke->where($where)->count();
        $page = new Page($count, C('PAGE_SIZE'), ['p' => I('p')]);
        $jokes = $Joke->relation(true)->where($where)
            ->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        $page_str = str_replace('/Home/Index/hot/', "/hot/{$type}/", $page->show());
        $this->assign('page', $page_str);
        $this->assign('user_joke', $jokes);
        $this->assign('type', $type);
        $this->display('index');
    }

    public function hotJoke()
    {
        $Joke = D('Joke');
        $count = $Joke->where(['status' => JokeModel::JOKE_STATUS_VALID])->count();
        $page = new Page($count, C('PAGE_SIZE'), ['p' => I('p')]);
        $jokes = $Joke->relation(true)->where(['status' => JokeModel::JOKE_STATUS_VALID])
            ->order('good_num desc,id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        $page_str = str_replace('/Home/Index/', '', $page->show());
        $this->assign('page', $page_str);
        $this->assign('user_joke', $jokes);
        $this->display('hot_joke');
    }

    /**
     * 文字笑话
     */
    public function text()
    {
        $Joke = D('Joke');
        $data = $Joke->findByType('text');
        $this->assign('page', $data['page']);
        $this->assign('user_joke', $data['jokes']);
        $this->assign('type', 'text');
        $this->display();
    }

    /**
     * 图片笑话
     */
    public function pic()
    {
        $Joke = D('Joke');
        $data = $Joke->findByType('pic');
        $this->assign('page', $data['page']);
        $this->assign('user_joke', $data['jokes']);
        $this->assign('type', 'pic');
        $this->display();
    }

    /**
     * 动态图片笑话
     */
    public function gif()
    {
        $Joke = D('Joke');
        $data = $Joke->findByType('gif');
        $this->assign('page', $data['page']);
        $this->assign('user_joke', $data['jokes']);
        $this->assign('type', 'gif');
        $this->display();
    }

    /**
     * 视频笑话
     */
    public function video()
    {
        $Joke = D('Joke');
        $data = $Joke->findByType('video');
        $this->assign('page', $data['page']);
        $this->assign('user_joke', $data['jokes']);
        $this->assign('type', 'video');
        $this->display();
    }

    /**
     * 展示标签页
     */
    public function showTags()
    {

        $this->display('show_tags');
    }

    /**
     * 根据指定的标签查找笑话
     */
    public function tag()
    {
        $tag=M('Tags')->find(I('id'));
        $Joke=D('Joke');
        $where=['status'=>JokeModel::JOKE_STATUS_VALID,'tags_id'=>I('id')];
        $count = $Joke->where($where)->count();
        $page = new Page($count, C('PAGE_SIZE'), ['p' => I('p')]);
        $jokes=$Joke->relation(true)->where($where)->
        limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
        $page_str = str_replace('/Home/Index/tag', I('id'), $page->show());
        $this->assign('user_joke',$jokes);
        $this->assign('tag_info',$tag);
        $this->assign('count',$count);
        $this->assign('page',$page_str);
        $this->display();
    }

    /**
     * 神回复
     */
    public function godReply()
    {
        $Joke = D('Joke');
        $where = ['status' => JokeModel::JOKE_STATUS_VALID, 'god_reply' => 1];
        $count = $Joke->where($where)->count();
        $page = new Page($count, C('PAGE_SIZE'), ['p' => I('p')]);
        $user_joke = $Joke->relation(true)->where($where)->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
        foreach ($user_joke as $key => $value) {
            $user_review_mod = D('Comment');
            $user_review = $user_review_mod->relation(true)->where('joke_id=' . $value['id'])->order('good_num desc')->find();
            $user_joke[$key]['god_reply_info'] = $user_review;
        }
        $this->assign('user_joke', $user_joke);
        $page_str = str_replace('/Home/Index/', '', $page->show());
        $this->assign('page', $page_str);
        $this->display('god_reply');
    }
}