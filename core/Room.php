<?php
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
        $val = json_decode($arg, true);
        $this->master = $val['master'];
        $this->name = $val['name'];
        $this->id   = $val['id'];
        $this->tag_users = self::TAG . $this->id;
        return $this;
	}

	public function join($uid){
        Log::debug('user ' . $uid . ' join ' . $this->tag_users);
        if($this->getSize() >= self::USER_MAX){
            Log::error("room $this->id is full");
            return false;
        }
        if($this->exist($uid)){
            Log::debug($uid . ' is exist in ' . $this->tag_users);
            return false;
        }
        $this->redis->sAdd($this->tag_users, $uid);
        return true;
	}

	public function kick($fromUid, $targetUid){
		$this->leave($targetUid);
	}

	public function leave($uid){
        Log::debug($uid.' leave '.$this->tag_users);
		$this->redis->sRem($this->tag_users, $uid);
        Log::debug($this->tag_users.' left '.$this->getSize());
        if($this->id != Zone::LOBBY_ROOM_ID && $this->getSize() < 1){
            $zone = new Zone();
            $zone->destoryRoom($this->id);
        }
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

	public function getSize(){
		return $this->redis->sSize($this->tag_users);
	}

	public function getUsers(){
		return $this->redis->sMembers($this->tag_users);
	}

	// public function setMaster($master){
	// 	if($this->master > 0){
	// 		unset($this->users[$this->master]);
	// 	}
	// 	$this->master = $master;
	// 	$this->join($master);
	// }

	// public function getMaster(){
	// 	return $master;
	// }

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