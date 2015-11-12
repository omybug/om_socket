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
        $sql = 'INSERT INTO item (item_id, uid, amount)VALUES (:itemId, :uid, :amount) ON DUPLICATE KEY UPDATE amount = amount + :amount';
        return $this->update($sql, array('itemId'=>$itemId, 'uid'=>$uid, 'amount'=>$amount, 'amount'=>$amount));
    }

    public function sub($uid, $itemId, $amount){
        if($amount < 1){
            return false;
        }
        $sql = 'UPDATE item SET amount = amount - :amount WHERE uid = :uid AND item_id = :$itemId AND amount > :amount';
        return $this->update($sql, array('amount'=>$amount, 'uid'=>$uid, 'itemId'=>$itemId,'amount'=>$amount));
    }
}