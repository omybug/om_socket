<?php
/**
 * User: omybug
 * Date: 15-10-12 21:56
 */
class UserDao extends \core\Dao{

    public function find($uid){

    }

    public function create($uid, $name){
        $sql = 'INSERT INTO user (uid,name) VALUES (:uid, :name)';
        return $this->insert($sql,array('uid'=>$uid, 'name'=>$name));
    }

    public function addMoney($uid, $money){
        if($money < 1){
            return false;
        }
        $sql = 'UPDATE user SET money = money + :money WHERE uid = :uid';
        return $this->db->update($sql, array('money'=>$money,'uid'=>$uid));
    }

    public function subMoney($uid, $money){
        if($money < 1){
            return false;
        }
        $sql = 'UPDATE user SET money = money - :money WHERE uid = :uid AND money > :money';
        return $this->db->update($sql, array('money'=>$money,'uid'=>$uid, 'money'=>$money));
    }
}