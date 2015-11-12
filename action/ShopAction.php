<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/10
 * Time: 15:16
 */

class ShopAction extends \core\Action{

    private $sitem;

    function __construct(){
        parent::__construct();
        $this->sitem = new ItemService($this);
    }

    public function buy(){
        if($this->sitem->add($this->uid, $this->data['itemId'], $this->data['amount'])){
            $m = new \core\Message('Shop','buy',array('ret'=>1));
            $this->send($m);
        }else{
            $m = new \core\Message('Shop','buy',array('ret'=>-1));
            $this->send($m);
        }
    }

    public function sell(){
        if($this->sitem->sub($this->uid, $this->data['itemId'], $this->data['amount'])){
            $m = new \core\Message('Shop','sell', array('ret'=>1));
            $this->send($m);
        }else{
            $m = new \core\Message('Shop','sell', array('ret'=>-1));
            $this->send($m);
        }
    }

}