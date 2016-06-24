<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 */
namespace Home\Controller;

use Common\Controller\BaseController;
use Think\Verify;
use Common\Model\TraceModel;
class JokeController extends BaseController
{
    public function index()
    {
        $this->_empty();
    }
    /**
     * 添加成功
     */
    public function comment()
    {
        if (IS_AJAX) {
            if (!$this->user)
                $this->ajaxReturn(['err' => 0, 'msg' => '请先登录!']);
            $Comment = D('Comment');
            if ($Comment->create()) {
                $comment = $Comment->addComment($this->user['id']);
                $this->ajaxReturn(['err' => 1, 'msg' => $comment]);
            } else {
                $this->ajaxReturn(['err' => 0, 'msg' => $Comment->getError()]);
            }
        }
    }

    /**
     * 评论点赞
     */
    public function commentUp()
    {
        if(IS_AJAX)
        {
            if (!$this->user) {
                $this->ajaxReturn(['err' => 0, 'msg'=>'请先登录']);
            }
            if($this->user['id']==D('Comment')->find(I('id'))['user_id'])
            {
                $this->ajaxReturn(['err'=>0,'msg'=>'不能点赞自己的评论']);
            }
            $Record = M('UserReviewRecord', 'cs_');
            if (!$Record->where(['user_id' => $this->user['id'], 'review_id' => I('id')])->count()) {
                $Record->add(['user_id' => $this->user['id'], 'review_id' => I('id'), 'created_time' => time()]);
                D('Trace')->insert($this->user['id'],10,'评论被点赞10金币',TraceModel::TRACE_TYPE_IN);
                D('Comment')->addAttribute(I('id'), 'good_num', 1);
                $this->ajaxReturn(['err' => 1, '点赞成功']);
            }
            $this->ajaxReturn(['err' => 0, '你已经点过赞了']);
        }
    }

    /**
     * 获得评论列表
     */
    public function commentList()
    {
        $Comment = D('Comment');
        $comments = $Comment->findList();
        $this->ajaxReturn(['err' => 1, 'msg' => $comments]);
    }

    /**
     * 笑话点赞或踩
     */
    public function upDown()
    {

        if (IS_AJAX) {
            if (!$this->user) {
                $this->ajaxReturn(['err' => 0, 'msg'=>'请先登录']);
            }
            if($this->user['id']==D('Joke')->find(I('id'))['user_id'])
            {
                $this->ajaxReturn(['err'=>0,'msg'=>'不能点赞或踩自己的笑话']);
            }
            $Record = M('UserRecord');
            if (!$Record->where(['user_id' => $this->user['id'], 'joke_id' => I('id')])
                ->where("type='good' or type='bad'")->count()) {
                $Record->add(['user_id' => $this->user['id'], 'joke_id' => I('id'),
                    'type' => I('type') == 'good' ? 'good' : 'bad', 'created_time' => time()]);
                D('Joke')->addAttribute(I('id'), I('type') == 'good' ? 'good_num' : 'bad_num', 1);
                if(I('type')==='good')
                {
                    $userId=D('Joke')->find(I('id'))['id'];
                    D('Trace')->insert($userId,10,'笑话被点赞获得10金币',TraceModel::TRACE_TYPE_IN);
                }
                $this->ajaxReturn(['err' => 1, 'msg' => '成功']);
            }
        }
    }

    /**
     * 打赏操作
     */
    public function award()
    {
        if (!$this->user) {
            $this->ajaxReturn(['err' => 0, 'msg'=>'请先登录']);
        }
        if (IS_AJAX) {
            $Record = M('UserRecord');
            if (!$Record->where(['user_id' => $this->user['id'], 'joke_id' => I('id')])->where('award!=0')->count()) {
                if($this->user['money']<I('fee'))
                {
                    $this->ajaxReturn(['err'=>0,'msg'=>'你没有足够的金币']);
                }
                if($this->user['id']==D('Joke')->find(I('id'))['user_id'])
                {
                    $this->ajaxReturn(['err'=>0,'msg'=>'不能打赏自己的笑话']);
                }
                $Record->add(['user_id' => $this->user['id'], 'joke_id' => I('id'),
                    'award' => I('fee'), 'created_time' => time()]);
                $Trace=D('Trace');
                D('Trace')->insert(I('id'),I('fee'),'笑话被打赏获得'.I('fee').'金币',TraceModel::TRACE_TYPE_IN);
                D('Trace')->insert($this->user['id'],I('fee'),'你打赏花费'.I('fee').'金币',TraceModel::TRACE_TYPE_OUT);
                D('User')->addAttribute(D('Joke')->where(['id'=>I('id')])->find()['user_id'],'money',I('fee'));
                D('User')->addAttribute($this->user['id'],'money',-1*intval(I('fee')));
                $this->ajaxReturn(['err' => 1, 'msg' => '成功']);
            }
            $this->ajaxReturn(['err'=>0,'msg'=>'您已经打赏过了']);
        }
    }

    /**
     * 关于积分
     */
    public function about()
    {
        $content=I('content');
        $this->display("about_{$content}");
    }
    /**
     * 验证码
     */
    public function verify()
    {
        (new Verify([
            'fontSize' => 40,    // 验证码字体大小
            'length' => 4,     // 验证码位数
        ]))->entry();
    }
}