<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/12
 * Time: 15:55
 */

class UserService {

    private static $TAG_OL = 'online';
    private static $TAG_TK = 'token';

    function __construct(){
    }

    /**
     * @param $account
     * @param $password
     * @return bool|int -2:账户不存在，-1：密码错误
     */
    public function login($account, $password){
        $loginDao = new LoginDao();
        $result = $loginDao->find($account);
        if(!$result){
            return -2;
        }
        if($result['password'] == md5($password)){
            $redis = \core\Redis::instance();
            $result['token'] = md5(uniqid(mt_rand(), true));
            //put user info into reids
            $data['uid']        = $result['id'];
            $data['account']    = $result['account'];
            $redis->sAdd(self::$TAG_OL, json_encode($data));
            $redis->set(self::$token.$result['token'], $result['id']);
            return $result['id'];
        }
        return -1;
    }

    /**
     * @return mixed 所有在线用户id
     */
    public function getOnlineUsers(){
        $data = $this->redis->sMembers(self::$TAG_OL);
        $users = array();
        foreach($data as $d){
            $u = json_decode($d, true);
            $users[$u['id']] = $u;
        }
        return $users;
    }

    /**
     * @return mixed 在线用户数量
     */
    public function getOnlineSize(){
        return $this->redis->sSize(self::$TAG_OL);
    }

    /**
     * 是否在线
     * @param $uid
     * @return bool
     */
    public function isOnline($uid){
        $users = getOnlineUsers();
        return !empty($users[$uid]);
    }

    /**
     * 获取用户ID
     * @param $token
     * @return int
     */
    public function getUid($token){
        return $this->redis->get(self::$TAG_TK . $token);
    }

    /**
     * @param $token
     * @param $uid
     */
    public function logout($token, $uid){
        $this->redis->delete(self::$TAG_TK . $token);
        $this->redis->sRem(self::$TAG_OL, $uid);
    }

    /**
     * @param $account
     * @param $password
     * @return int|mixed -1:账户已存在
     */
    public function register($account, $password){
        $loginDao = new LoginDao();
        $result = $loginDao->find($account);
        if($result){
            return -1;
        }
        return $loginDao->create($account, $password);
    }

}