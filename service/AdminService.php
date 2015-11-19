<?php
/**
 * User: omybug
 * Date: 15-11-9 21:34
 */

class AdminService extends \core\Service{

    CONST BCU   = 'ban_chat_uids';
    CONST BCI   = 'ban_chat_ips';

    private $redis;

    function __construct($action = null){
        parent::__construct($action);
        $this->redis = \core\Redis::instance();
    }

    public function addStats($data){
        $dadmin = new AdminDao();
        $dadmin->addStats($data);
    }

    /**
     * 禁言
     * @param array $data
     */
    public function banChat($data){
        if(array_key_exists('uid',data)){
            $this->redis->sAdd(self::BCU, $data['uid']);
        }
        if(array_key_exists('ip',$this->data)){
            $this->redis->sAdd(self::BCI, $data['ip']);
        }
    }

    /**
     * @param $data
     */
    public function isBanChat($data){
        if(array_key_exists('uid',data)){
            $this->redis->sIsMember(self::BCU, $data['uid']);
        }
        if(array_key_exists('ip',$this->data)){
            $this->redis->sIsMember(self::BCI, $data['ip']);
        }
    }

    /**
     * @param $data
     */
    public function unBanChat($data){
        if(array_key_exists('uid',$this->data)){
            $this->redis->sRem(self::BCU, $data['uid']);
        }
        if(array_key_exists('ip',$this->data)){
            $this->redis->sRem(self::BCI, $data['ip']);
        }
    }

    public function getBanChatUids(){
        $this->redis->sMembers(self::BCU);
    }

    public function getBanChatIps(){
        $this->redis->sMembers(self::BCI);
    }

    /**
     * 强制下线
     * @param $uid
     */
    public function kick($uid){


    }
} 