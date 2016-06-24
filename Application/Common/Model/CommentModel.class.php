<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 */
namespace Common\Model;
use Think\Model\RelationModel;
use Think\Page;
class CommentModel extends RelationModel
{
    protected $tablePrefix = 'cs_';
    protected $tableName = 'user_review';
    const COMMENT_STATUS_NEW = 1;//新添加
    const COMMENT_STATUS_VALID = 2;//可显示
    const COMMENT_STATUS_DEL = 3;//删除
    protected $_link = [
        'Joke' => self::BELONGS_TO,
        'User' => self::BELONGS_TO
    ];
    protected $_validate = [
        ['content', 'checkContent', '内容长度不合法(1~140字符)', self::MUST_VALIDATE, 'callback'],
    ];
    protected $_auto = [
        ['created_time', NOW_TIME],
        ['status', 'C', self::MODEL_INSERT, 'function', 'REVIEW_STATUS_UNAUDIT'],
    ];
    
    public function checkContent($content)
    {
        if ($content == null || $content == '' || mb_strlen($content) > 140)
            return false;
        else
            return true;
    }

    /**
     * 添加评论
     * @param $user_id
     */
    public function addComment($user_id)
    {
        $data=$this->data();
        $data['user_id']=$user_id;
        $id=$this->add($data);
        D('Joke')->addAttribute($data['joke_id'],'review_num',1);
        return $this->relation(true)->find($id);
    }

    /**
     * 查找指定笑话的评论
     * @param $id
     * @return mixed
     */
    public function findByJoke($id)
    {
        return $this->relation(true)->where(['status'=>self::COMMENT_STATUS_VALID,'joke_id'=>$id])->order('id desc')->select();
    }

    /**
     * 属性增加值
     * @param $id
     * @param $attr
     * @param $count
     */
    public function addAttribute($id,$attr,$count)
    {
        $this->where(['id'=>$id])->setInc($attr,$count);
    }
    public function findList()
    {
        $Comment = D('Comment');
        $where=['status' => self::COMMENT_STATUS_VALID,'joke_id'=>I('id')];
        $count = $Comment->where($where)->count();
        $page = new Page($count, C('PAGE_SIZE'), ['p' => I('p')]);
        $comments = $Comment->relation(true)->where($where)
            ->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
//        $page_str = str_replace('/Home/Index/index/', '/jokes/', $page->show());
//        return ['comments'=>$comments,'page'=>$page_str];
        return $comments;
    }
}