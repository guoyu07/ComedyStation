<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 */
namespace Common\Model;
use Think\Model\RelationModel;
class TraceModel extends RelationModel
{
    protected $tablePrefix = 'cs_';
    protected $tableName = 'user_trace';
    const TRACE_TYPE_IN=1;//收入
    const TRACE_TYPE_OUT=2;//支出
    protected $_link = [
        'User' => self::BELONGS_TO
    ];

    /**
     * 添加痕迹
     * @param $userId
     * @param $val
     * @param $content
     * @param $type
     */
    public function insert($userId,$val,$content,$type)
    {
        $this->add(['user_id'=>$userId,'value'=>$val,'content'=>$content,'type'=>$type,'created_time'=>time()]);
    }
}