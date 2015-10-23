<?php
namespace core;

class Room{

	private $roomId;
	private $roomName;
	//房主
	private $master;
	//房间最大容量
	private static $USER_MAX = 100000;
	private $tag;
    private $tag_info;
    private $tag_users;

	private $redis;

	function __construct(){
        $this->redis = Redis::instance();
	}

	public function create($roomId,$roomName,$master=0){
		$this->roomId = $roomId;
		$this->roomName = $roomName;
		// if($master > 0){
		// 	array_push($this->users, $master);
			$this->master = $master;
		// }
		$this->tag_users = 'room_users_'.$roomId;
        $this->tag_info  = 'room_info_'.$roomId;
        $roomInfo = array('id'=>$roomId, 'name'=>$roomName, 'master'=>$master);
        $this->redis->set($this->tag_info,json_encode($roomInfo));
        return $this;
	}

	public function init($roomId){
		$this->roomId = $roomId;
        $this->tag_users = 'room_users_'.$roomId;
        $this->tag_info  = 'room_info_'.$roomId;
        $val = $this->redis->get($this->tag_info);
        if(isset($val)){
        	$info = json_decode($val,true);
        	$this->roomName = $info['name'];
        	$this->master = $info['master'];
        }
        return $this;
	}

	public function join($uid){
		if($this->getSize() >= self::$USER_MAX){
            Log::error("room $this->roomId is full");
			return false;
		}
		if($this->exist($uid)){
            Log::debug($uid . ' is exist in lobby');
            return false;
        }
        $this->redis->sAdd($this->tag_users, $uid);
        return true;
	}

	public function kick($fromUid, $targetUid){
		$this->leave($targetUid);
	}

	public function leave($uid){
		$this->redis->sRem($this->tag_users, $uid);
	}

    public function getRoomId(){
        return $this->roomId;
    }

    public function getRoomName(){
        return $this->roomName;
    }

	public function getRoomInfo(){
		return array(
			'id'=>$this->roomId,
            'name'=>$this->roomName,
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
		while($this->redis->sPop($this->tag)){

		}
		$this->redis->delete($this->tag_info);
		unset($this->roomId);
		unset($this->roomName);
	}
}

?>