<?php
/**
 * User: omybug
 * Date: 15-11-10 22:37
 */

namespace service;

use dao\ItemDao;
use dao\UserDao;

class ItemService extends Service{

    public function add($uid, $itemId, $amount){
//        $_st = \core\Util::timestamp();
        $duser = new UserDao();
//        $duser->openProfiles();
//        $this->begin();
        if($duser->subMoney($uid, $itemId * $amount)){
            $ditem = new ItemDao();
            $ditem->add($uid, $itemId, $amount);
//            $this->commit();
//            var_dump($ditem->showProfiles());
            return true;
        }else{
//            $this->rollback();
        }
        return false;
    }

    public function sub($uid, $itemId, $amount){
//        $this->begin();
        $ditem = new ItemDao();
        if($ditem->sub($uid, $itemId, $amount)){
            $duser = new UserDao();
            $duser->addMoney($uid, $itemId * $amount);
//            $this->commit();
            return true;
        }else{
//            $this->rollback();
        }
        return false;
    }
}