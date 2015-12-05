<?php
/**
 * User: omybug
 * Date: 15-12-5 21:59
 */

namespace core;


class UserManager {

    const TAG_OL = 'online';
    const TAG_TK = 'token';
    private $redis;
    private static $instance = null;

    function __construct(){
        $this->redis = Redis::instance();
    }

    public static function instance(){
        if(empty(self::$instance)){
            self::$instance = new UserManager();
        }
        return self::$instance;
    }

    /**
     * @param $fd
     * @param $uid
     */
    public function bindUid($fd, $uid){
        $this->redis->hSet(self::TAG_OL, $uid, $fd);
        $this->redis->hSet(self::TAG_TK, $fd, $uid);
    }

    /**
     * @param $fd
     * @return uid
     */
    public function getBindUid($fd){
        $uid = $this->redis->hGet(self::TAG_TK, $fd);
        return $uid;
    }

    /**
     * @param string|array $arg
     * @return array|null|string
     */
    public function getBindFd($arg){
        if(is_array($arg)){
            return $this->redis->hMGet(self::TAG_OL, $arg);
        }else{
            return $this->redis->hGet(self::TAG_OL, $arg);
        }
    }

    /**
     * @param $fd
     */
    public function unBind($fd){
        $uid = $this::getBindUid($fd);
        $this->redis->hDel(self::TAG_TK, $fd);
        $this->redis->hDel(self::TAG_OL, $uid);
    }

    /**
     * @return array 所有在线用户id
     */
    public function getOnlineUserIds(){
        return $this->redis->hKeys(self::TAG_OL);
    }

    /**
     * @return int 在线用户数量
     */
    public function getOnlineUserSize(){
        return $this->redis->hLen(self::TAG_OL);
    }

    /**
     * 是否在线
     * @param $uid
     * @return bool
     */
    public function isOnline($uid){
        return $this->redis->hExists(self::TAG_OL, $uid);
    }

} 