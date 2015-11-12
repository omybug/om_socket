<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/12
 * Time: 15:55
 */

class UserService extends \core\Service{

    private static $TAG_OL = 'online';
    private static $TAG_TK = 'token';
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
    public function bindUid($fd, $uid){
        $this->redis->hSet(self::$TAG_OL, $uid, $fd);
        $this->redis->hSet(self::$TAG_TK, $fd, $uid);
    }

    /**
     * @param $fd
     * @return uid
     */
    public function getBindUid($fd){
        return $this->redis->hGet(self::$TAG_TK, $fd);
    }

    /**
     * @param string|array $arg
     * @return array|null|string
     */
    public function getBindFd($arg){
        if(is_array($arg)){
            return $this->redis->hMGet(self::$TAG_OL, $arg);
        }else{
            return $this->redis->hGet(self::$TAG_OL, $arg);
        }
    }

    /**
     * @param $fd
     */
    public function unBind($fd){
        $uid = $this::getBindUid($fd);
        $this->redis->hDel(self::$TAG_TK, $fd);
        $this->redis->hDel(self::$TAG_OL, $uid);
    }

    /**
     * @return array 所有在线用户id
     */
    public function getOnlineUserIds(){
        return $this->redis->hKeys(self::$TAG_OL);
    }

    /**
     * @return int 在线用户数量
     */
    public function getOnlineUserSize(){
        return $this->redis->hLen(self::$TAG_OL);
    }

    /**
     * 是否在线
     * @param $uid
     * @return bool
     */
    public function isOnline($uid){
        return $this->redis->hExists(self::$TAG_OL, $uid);
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
            if($this->isOnline($uid)){
                $oldFd = $this->logout($uid);
                $this->offline($oldFd);
                //多地同时登录处理
                if($this->action->exist($oldFd)) {
//                    $this->action->sendToUser($oldFd);
                    $this->action->close($oldFd);
                }
            }
            $this->bindUid($fd, $result['id']);
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
        return $oldFd;
    }

    public function offline($fd){
        $uid = $this->getBindUid($fd);
        $this->unBind($fd);
        $zone = new \core\Zone();
        $rooms = $zone->getRooms();
        foreach($rooms as $room){
            $room->leave($uid);
        }
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

    /**
     * @param $uid
     * @param $name
     * @return mixed
     */
    public function createRole($uid, $name){
        $duser = new UserDao();
        return $duser->create($uid, $name);
    }
}