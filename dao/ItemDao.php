<?php
/**
 * User: omybug
 * Date: 15-11-10 21:02
 */
class ItemDao extends \core\Dao{

    public function add($uid, $itemId, $amount){
        if($amount < 1){
            return false;
        }
        $sql = 'INSERT INTO item (uid, item_id, amount)VALUES (:a1, :a2, :a3) ON DUPLICATE KEY UPDATE amount = amount + :a4';
        return $this->I($sql, array('a1'=>$uid, 'a2'=>$itemId, 'a3'=>$amount, 'a4'=>$amount));
    }

    public function sub($uid, $itemId, $amount){
        if($amount < 1){
            return false;
        }
        $sql = 'UPDATE item SET amount = amount - :a1 WHERE uid = :a2 AND item_id = :a3 AND amount > :a4';
        return $this->U($sql, array('a1'=>$amount, 'a2'=>$uid, 'a3'=>$itemId,'a4'=>$amount));
    }
}