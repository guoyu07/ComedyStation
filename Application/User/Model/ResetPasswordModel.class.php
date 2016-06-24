<?php
namespace User\Model;

/**
 * Created by PhpStorm.
 * User: noble4cc
 */
use User\Model\UserModel;

class ResetPasswordModel extends UserModel
{
    public $id;
    protected $_validate = [
        ['old_password', 'checkOldPassword', '旧密码不正确', self::MUST_VALIDATE, 'callback'],
        ['password', '6,20', '密码长度不合法(6~20字符)', self::MUST_VALIDATE, 'length'],
    ];
    protected $_auto = [
        ['password', 'password_md5', self::MODEL_BOTH, 'function'],
    ];
    public function checkOldPassword()
    {
        if ($this->where(['id' => $this->id])->find()['password'] != password_md5(I('old_password')))
            return false;
        return true;
    }
    public function setPassword()
    {
        $this->where(['id' => $this->id])->save(['password' => $this->data()['password']]);
    }
}