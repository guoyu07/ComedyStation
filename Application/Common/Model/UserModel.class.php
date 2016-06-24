<?php
namespace Common\Model;

/**
 * Created by PhpStorm.
 * User: noble4cc
 */
use Think\Model;

class UserModel extends Model
{
    protected $tablePrefix='cs_';
    protected $tableName='user';

    /**
     * 更新用户的数据
     * @param $id
     * @param $data
     */
    public function updateUser($id,$data)
    {
        $this->where(['id'=>$id])->save($data);
    }

    /**
     * 控制数字属性的增加和介绍
     * @param $id
     * @param $attr
     * @param $count
     */
    public function addAttribute($id,$attr,$count)
    {
        $this->where(['id'=>$id])->setInc($attr,$count);
    }
}