<?php
namespace User\Model;

/**
 * Created by PhpStorm.
 * User: noble4cc
 */
use Common\Model\JokeModel;

class JokePublishModel extends JokeModel
{
    public $userId;
    protected $_validate = [
        ['title', 'require', '标题不能为空', self::MUST_VALIDATE],
        ['content', 'checkContent', '内容不能为空', self::MUST_VALIDATE, 'callback'],
        ['title', 'checkValid', '输入标题不合法', self::MUST_VALIDATE, 'callback'],
        ['is_video', 'checkIsVideo', '视频连接不能为空', self::MUST_VALIDATE, 'callback'],
    ];
    protected $_auto = [
        ['type', 'getType', self::MODEL_BOTH, 'callback'],
        ['created_time', NOW_TIME,],
        ['status', self::JOKE_STATUS_NEW],
    ];

    /**
     * 获得笑话类型
     * @return int
     */
    public function getType()
    {
        if (I('is_video')) {
            return self::JOKE_TYPE_VEDIO;
        } else {
            if (I('image')) {
                $image = I('image');
                $ext = substr($image, strrpos($image, '.') + 1);
                if ($ext == 'gif') {
                    return self::JOKE_TYPE_GIF;
                }
                return self::JOKE_TYPE_PIC;
            } else {
                return self::JOKE_TYPE_TEXT;
            }
        }

    }

    /**
     * 检测视频URL
     * @return bool
     */
    public function checkIsVideo()
    {
        if (I('is_video') && !I('url')) {
            return false;
        }
        return true;
    }

    public function checkValid($str)
    {
        if (!$str && strlen($str) == 0) {
            return false;
        }
        return true;
    }

    public function checkContent($content)
    {
        if ($content === null || $content === '') {
            if (I('is_video'))
                return true;
            else
                return false;
        }
        return true;
    }

    public function publish($id)
    {
        $data = $this->data();
        if ($data['type'] == self::JOKE_TYPE_VEDIO) {
            $data['content'] = I('url');
        }
        $data['user_id'] = $id;
        $this->add($data);
    }
}