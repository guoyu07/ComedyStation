<?php
namespace Common\Model;

/**
 * Created by PhpStorm.
 * User: noble4cc
 */
use Think\Model\RelationModel;
use Think\Page;

class JokeModel extends RelationModel
{
    const JOKE_TYPE_TEXT = 1;//文字类型
    const JOKE_TYPE_PIC = 2;//图片类型
    const JOKE_TYPE_GIF = 3;//动态图片类型
    const JOKE_TYPE_VEDIO = 4;//视频类型
    const JOKE_STATUS_NEW = 1;//新添加
    const JOKE_STATUS_VALID = 2;//可显示
    const JOKE_STATUS_DEL = 3;//删除
    protected $tablePrefix = 'cs_';
    protected $tableName = 'user_joke';
    protected $_link = [
        'User' => self::BELONGS_TO
    ];

    /**
     * 查找指定类型的笑话,并分页
     * @param $type
     * @return array
     */
    public function findByType($type)
    {
        switch ($type) {
            case 'text':
                $t = self::JOKE_TYPE_TEXT;
                break;
            case 'pic':
                $t = self::JOKE_TYPE_PIC;
                break;
            case 'gif':
                $t = self::JOKE_TYPE_GIF;
                break;
            case 'video':
                $t = self::JOKE_TYPE_VEDIO;
                break;
        }
        $where = ['status' => JokeModel::JOKE_STATUS_VALID, 'type' => $t];
        $count = $this->where($where)->count();
        $page = new Page($count, C('PAGE_SIZE'), ['p' => I('p')]);
        $jokes = $this->relation(true)->where($where)
            ->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        $page_str = str_replace('/Home/Joke/index/', '/' . $type . '/', $page->show());
        return ['jokes' => $jokes, 'page' => $page_str];
    }

    /**
     * 根据ID获得可用状态的笑话
     * @param $id
     */
    public function findValid($id)
    {
        return $this->relation(true)->where(['status' => self::JOKE_STATUS_VALID, 'id' => $id])->find();
    }

    /**
     * 查找上一个笑话
     * @param $id
     * @return mixed
     */
    public function findPreValid($id)
    {
        return $this->where(['status' => self::JOKE_STATUS_VALID])->where("id<$id")->order('id desc')->find();
    }

    /**
     * 查找下一个笑话
     * @param $id
     * @return mixed
     */
    public function findNextValid($id)
    {
        return $this->where(['status' => self::JOKE_STATUS_VALID])->where("id>$id")->find();
    }

    /**
     * 改变指定属性的值
     * @param $id
     * @param $attr
     * @param $count
     */
    public function addAttribute($id,$attr,$count)
    {
        $this->where(['id'=>$id])->setInc($attr,$count);
    }

    /**
     * 获得推荐笑话
     * @param $type
     * @return mixed
     */
    public function getRecommend($type)
    {
        $where=['status'=>self::JOKE_STATUS_VALID,'recommend'=>1,'type'=>$type];
        return $this->where($where)->limit(10)->select();
    }
}