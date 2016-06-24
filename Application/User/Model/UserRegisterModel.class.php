<?php
namespace User\Model;
/**
 * Created by PhpStorm.
 * User: noble4cc
 */
use User\Model\UserModel;
class UserRegisterModel extends UserModel
{
    protected $_validate = [
        ['username', '1,20', '用户名长度不合法(1~20字符)', self::MUST_VALIDATE, 'length'],
        ['username', '', '用户名已存在', self::MUST_VALIDATE, 'unique'],
        ['password', '6,20', '密码长度不合法(6~20字符)', self::MUST_VALIDATE, 'length'],
        ['email', 'email', '邮箱格式不合法'],
        ['email', '1,32', '邮箱长度不合法', self::MUST_VALIDATE, 'length'],
        ['email', '', '邮箱已存在', self::MUST_VALIDATE, 'unique'],
    ];
    protected $_auto = [
        ['password', 'password_md5', self::MODEL_BOTH, 'function'],
        ['created_time', NOW_TIME,],
        ['last_login_time', NOW_TIME,],
        ['last_login_ip', 'get_client_ip', self::MODEL_BOTH, 'function'],
        ['register_ip', 'get_client_ip', self::MODEL_BOTH, 'function'],
        ['status', 'C', self::MODEL_INSERT, 'function', 'USER_STATUS_UNACTIVATE'],
        ['money', 'C', self::MODEL_INSERT, 'function', 'USER_DEFAULT_MONEY'],
    ];
    /**
     * 用户注册
     * @return bool|string
     */
    public function register()
    {
        $this->add();
    }
}
