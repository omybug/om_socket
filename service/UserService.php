<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/12
 * Time: 15:55
 */

class UserService extends \core\Service{

    private static $TAG_OL = 'online';
    private static $TAG_TK = 'token_';
    private $redis  = '';


    /**
     * @param \core\Action $action
     */
    function __construct($action = null){
        parent::__construct($action);
        $this->redis = \core\Redis::instance();
    }

    /**
     * @param $fd
     * @param $uid
     */
    public function setBindUid($fd, $uid){
        $this->redis->set(self::$TAG_TK.$fd, $uid);
    }

    /**
     * @param $fd
     */
    public function getBindUid($fd){
        $this->redis->get(self::$TAG_TK.$fd);
    }

    /**
     * @param string|array $uid
     * @return array|null|string
     */
    public function getBindFd($uid){
        $res = $this->redis->keys(self::$TAG_TK.'*');
        $this->redis->transform($res);
        if(is_array($uid)){
            $fds = array();
            foreach($res as $r){
                $_uid = $this->redis->get($r);
                foreach($uid as $u){
                    if($u == $_uid){
                        $oldFd = substr($r,strlen(self::$TAG_TK));
                        $fds[] = $oldFd;
                    }
                }
            }
            return $fds;
        }else{
            foreach($res as $r){
                $_uid = $this->redis->get($r);
                if($uid == $_uid){
                    $oldFd = substr($r,strlen(self::$TAG_TK));
                    return $oldFd;
                }
            }
        }
        return null;
    }

    /**
     * @param $fd
     */
    public function removeBindUid($fd){
        $this->redis->delete(self::$TAG_TK.$fd);
    }

    /**
     * @param $uid
     */
    public function addOnlineUserId($uid){
        $this->redis->sAdd(self::$TAG_OL, $uid);
    }

    /**
     * @return array 所有在线用户id
     */
    public function getOnlineUserIds(){
        return $this->redis->sMembers(self::$TAG_OL);
    }

    /**
     * @return int 在线用户数量
     */
    public function getOnlineUserSize(){
        return $this->redis->sSize(self::$TAG_OL);
    }

    /**
     * @param $uid
     */
    public function removeOnlineUser($uid){
        $this->redis->sRemove(self::$TAG_OL , $uid);
    }

    /**
     * 是否在线
     * @param $uid
     * @return bool
     */
    public function isOnline($uid){
        return $this->redis->sIsMember(self::$TAG_OL, $uid);
    }


    /**
     * @param $account
     * @param $password
     * @param $fd
     * @return bool|int -2:账户不存在，-1：密码错误
     */
    public function login($account, $password, $fd){
        $loginDao = new LoginDao();
        $result = $loginDao->find($account);
        if(!$result){
            return -2;
        }
        if($result['password'] == md5($password)){
            $uid = $result['id'];
//            $result['token'] = md5(uniqid(mt_rand(), true));
//            put user info into reids
//            $data['uid']        = $result['id'];
//            $data['fd']         = $fd;
//            $data['token']      = $result['token'];
//            $data['account']    = $result['account'];
//            $this->redis->sAdd(self::$TAG_OL, json_encode($data));
            if($this->isOnline($uid)){
                $oldFd = $this->logout($uid);
                if($this->action->exist($oldFd)) {
                    $this->action->sendToUser($oldFd, '您已在别处登录！');
                    $this->action->close($oldFd);
                }
            }
            $this->addOnlineUserId($uid);
            $this->setBindUid($fd, $result['id']);
            unset($result['password']);
            return $result;
        }
        return -1;
    }

    /**
     * 注销
     * @param $uid
     * @return string
     */
    public function logout($uid){
        $oldFd = $this->getBindFd($uid);
        $this->removeBindUid($oldFd);
        $this->removeOnlineUser($uid);
        return $oldFd;
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
        return $loginDao->create($account, md5($password));
    }
}