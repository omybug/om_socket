<?php
/**
 * User: omybug
 * Date: 15-11-10 22:37
 */

class ItemService extends \core\Service{

    public function add($uid, $itemId, $amount){
        $duser = new UserDao();
        $this->begin();
        if($duser->subMoney($uid, $itemId * $amount)){
            $ditem = new ItemDao();
            if($ditem->add($uid, $itemId, $amount)){
                $this->commit();
            }
        }
        $this->rollback();
        return false;
    }

    public function sub($uid, $itemId, $amount){
        $duser = new UserDao();
        $this->begin();
        if($duser->addMoney($uid, $itemId * $amount)){
            $ditem = new ItemDao();
            if($ditem->sub($uid, $itemId, $amount)){
                $this->commit();
            }
        }
        $this->rollback();
        return false;
    }
}