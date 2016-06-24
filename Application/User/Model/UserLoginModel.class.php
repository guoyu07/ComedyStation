<?php
namespace User\Model;

/**
 * Created by PhpStorm.
 * User: noble4cc
 */
use Common\Model\UserModel;
class UserLoginModel extends UserModel
{
    private $user;
    //前台传过来的是用户名,但是也是允许使用邮箱进行登录,也就是说username也可能是邮箱
    protected $_validate = [
        ['username', 'require', '请填写用户名', self::MUST_VALIDATE],
        ['password', 'require', '请填写密码', self::MUST_VALIDATE],
        ['username', '1,20', '用户名长度不合法(1~20字符)', self::MUST_VALIDATE, 'length'],
        ['password', '6,20', '密码长度不合法(6~20字符)', self::MUST_VALIDATE, 'length'],
        ['password', 'checkPassword', '用户名或密码错误', self::MUST_VALIDATE, 'callback'],
    ];

    /**
     * 验证密码
     * @return bool
     */
    public function checkPassword()
    {
        if (strpos(I('username'), '@') !== false) {
            $user = $this->where(['email' => I('username')])->find();
        } else {
            $user = $this->where(['username' => I('username')])->find();
        }
        if (is_array($user) && password_md5(I('password')) === $user['password']) {
            $this->user = $user;
            return true;
        }
        return false;
    }

    /**
     * 用户登陆
     * @return bool|string
     */
    public function login()
    {
        $login_time = time();
        if (isset($_POST['is_save']) && $_POST['is_save'] == 30) {
            $day = 30;
            $time = 3600 * 24 * $day;
            $key = md5($this->user['password']);
            cookie('user[id]', $this->user['id'], $time);
            cookie('user[username]', $this->user['username'], $time);
            cookie('user[login_time]', $login_time, $time);
            cookie('user[key]', $key, $time);
        }
        $_SESSION['id'] = $this->user['id'];
        $_SESSION['expire'] = time() + C('SESSION_EXPIRE');
        $_SESSION['start_time'] = time();
        $_SESSION['user'] = $this->user;
        $this->where(['id' => $this->user['id']])
            ->save(['last_login_time' => time(), 'last_login_ip' => get_client_ip()]);

    }
}
