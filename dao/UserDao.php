<?php
/**
 * User: omybug
 * Date: 15-10-12 21:56
 */
class UserDao extends \core\Dao{

    public function findByName($name){
        $sql = 'SELECT * FROM user WHERE name = :a1';
        return $this->S($sql,array('a1'=>$name));
    }

    public function create($uid, $name){
        \core\Log::sql("INSERT INTO user (uid,name) VALUES ($uid, $name)");
        $sql = 'INSERT INTO user (uid,name) VALUES (:uid, :name)';
        return $this->I($sql,array('uid'=>$uid, 'name'=>$name));
    }

    public function addMoney($uid, $money){
        if($money < 1){
            return false;
        }
        $sql = 'UPDATE user SET money = money + :money WHERE uid = :uid';
        return $this->U($sql, array('money'=>$money,'uid'=>$uid));
    }

    public function subMoney($uid, $money){
        if($money < 1){
            return false;
        }
        $sql = 'UPDATE user SET money = money - :a1 WHERE uid = :a2 AND money > :a3';
        return $this->U($sql, array('a1'=>$money,'a2'=>$uid, 'a3'=>$money));
    }
}