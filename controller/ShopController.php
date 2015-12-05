<?php
/**
 * User: omybug
 * Date: 15-11-10 21:16
 */

namespace controller;


use core\Message;
use service\ItemService;

class ShopController extends Controller{

    /**
     * @var ItemService
     */
    private $sitem;

    function __construct(){
        parent::__construct();
        $this->sitem = new ItemService($this);
    }

    public function buy(){
        if($this->sitem->add($this->uid, $this->data['itemId'], $this->data['amount'])){
            $m = new Message('Shop@buy',array('ret'=>1));
            $this->send($m);
            return true;
        }else{
            $m = new Message('Shop@buy',array('ret'=>-1));
            $this->send($m);
        }
        return false;
    }

    public function sell(){
        if($this->sitem->sub($this->uid, $this->data['itemId'], $this->data['amount'])){
            $m = new Message('Shop@sell', array('ret'=>1));
            $this->send($m);
            return true;
        }else{
            $m = new Message('Shop@sell', array('ret'=>-1));
            $this->send($m);
        }
        return false;
    }

}