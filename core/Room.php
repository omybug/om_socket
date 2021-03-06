<?php
/**
 * User: omybug
 * Date: 15-10-5 14:54
 */
namespace core;

class Room{

    private $id;
    private $name;
    //房主
    private $master;
    //房间最大容量
    const USER_MAX = 100000;
    const TAG = 'room_';
    private $tag_users;

    private $redis;

    function __construct(){
        $this->redis = Redis::instance();
    }

    public function create($roomId,$roomName,$master=0){
        $this->id       = $roomId;
        $this->name     = $roomName;
        $this->master   = $master;
        $this->tag_users = self::TAG . $this->id;
        return $this;
    }

    public function init($arg){
        if(empty($arg)){
            return null;
        }
        $val = json_decode($arg, true);
        $this->master = $val['master'];
        $this->name = $val['name'];
        $this->id   = $val['id'];
        $this->tag_users = self::TAG . $this->id;
        return $this;
    }

    public function join($uid){
        if($this->getSize() >= self::USER_MAX){
            Log::error("room $this->id is full");
            return false;
        }
        if($this->exist($uid)){
            return false;
        }
        Log::log('user ' . $uid . ' join ' . $this->tag_users);
        $this->redis->sAdd($this->tag_users, $uid);
        $this->updateRoomInfo();
        return true;
    }

    public function kick($fromUid, $targetUid){
        $this->leave($targetUid);
    }

    public function leave($uid){
        $this->redis->sRem($this->tag_users, $uid);
        if($this->id != Zone::LOBBY_ROOM_ID && $this->getSize() < 1){
            $zone = new Zone();
            $zone->destoryRoom($this->id);
        }
        $this->updateRoomInfo();
    }

    public function getRoomId(){
        return $this->id;
    }

    public function getRoomName(){
        return $this->name;
    }

    public function getRoomInfo(){
        return array(
            'id'=>$this->id,
            'name'=>$this->name,
            'master'=>$this->master,
            'amount'=>$this->getSize()
        );
    }

    private function updateRoomInfo(){
        $zone = new Zone();
        $zone->updateRoom($this);
    }

    public function getSize(){
//        if(!$this->redis->exists($this->tag_users)){
//            return 0;
//        }
        $size = $this->redis->sSize($this->tag_users);
//        if(empty($size)){
//            return 0;
//        }
        return $size;
    }

    public function getUsers(){
        return $this->redis->sMembers($this->tag_users);
    }

    public function setMaster($master){
        if($this->master > 0){
            unset($this->users[$this->master]);
        }
        $this->master = $master;
    }

    public function getMaster(){
        return $this->master;
    }

    public function exist($uid){
        return $this->redis->sIsMember($this->tag_users, $uid);
    }

    public function destory(){
        $this->redis->delete($this->tag_users);
        unset($this->id);
        unset($this->name);
    }
}

?>