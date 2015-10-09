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

	private $redis;

	function __construct(){
        $this->redis = \core\Redis::instance();
	}

	public function create($roomId,$roomName,$master=0){
		$this->roomId = $roomId;
		$this->roomName = $roomName;
		// if($master > 0){
		// 	array_push($this->users, $master);
			$this->master = $master;
		// }
		$this->tag = 'room'.$roomId.'users';
        $roomInfo = array('roomId'=>$roomId, 'roomName'=>$roomName, 'roomMaster'=>$master);
        $this->redis->set('room'.$roomId.'info',json_encode($roomInfo));
        return $this;
	}

	public function init($roomId){
		$this->roomId = $roomId;
        $val = $this->redis->get('room'.$roomId.'info');
        if(isset($val)){
        	$info = json_decode($val,true);
        	$this->roomName = $info['roomName'];
        	$this->master = $info['roomMaster'];
        }
        return $this;
	}

	public function join($uid){
		if($this->getSize() >= self::USER_MAX){
			echo "room is full\n";
			return false;
		}
		if(!$this->exist($uid)){
			$this->redis->sAdd($this->tag, $uid);
			return true;
		}
		return false;
	}

	public function kick($fromUid, $targetUid){
		$this->leave($targetUid);
	}

	public function leave($uid){
		$this->redis->sRem($this->tag, $uid);
	}

	public function getRoomInfo(){
		return array(
			'roomId'=>$this->roomId,'roomName'=>$this->roomName,
			'amount'=>$this->getSize()
		);
	}

	public function getSize(){
		return $this->redis->sSize($this->tag);
	}

	public function getUsers(){
		return $this->redis->sMembers($this->tag);
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
		return $this->redis->sIsMember($this->tag, $uid);
	}

	public function destory(){
		while($this->redis->sPop($this->tag)){

		}
		$this->redis->delete('room'.$this->roomId.'info');
		unset($this->roomId);
		unset($this->roomName);
	}
}

?>