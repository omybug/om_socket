<?php
/**
 * User: omybug
 * Date: 15-11-17 13:29
 */

require_once 'TestBase.php';

class StressTest extends TestBase{

    private $mark = 0;
    private $user;

    function connect(){
        $this->login();
    }

    function receive($data){
        $msg = json_decode($data,true);
        if($msg['f'] == 'login'){
            $this->createRole();
        }
        if($msg['f'] == 'createRole'){
            $this->mark = 1;
        }
        sleep(1);
        if($this->mark >= 1){
            if(rand(1,2) == 1){
                $this->buy();
            }else{
                $this->sell();
            }
            $this->mark = $this->mark + 1;
        }
    }

    function setAccount($user){
        $this->user = $user;
    }

    function login(){
        $data = array('a'=>'Home','f'=>'login','d'=>array('account'=>$this->user,'password'=>'000000'));
        $this->send($data);
    }

    function buy(){
        $amount = rand(1,10);
        $itemId = rand(1,10);
        $data = array('a' => 'Shop', 'f' => 'buy', 'd' => array('itemId' => $itemId,'amount'=>$amount));
        $this->send($data);
    }

    function sell(){
        $amount = rand(1,10);
        $itemId = rand(1,10);
        $data = array('a' => 'Shop', 'f' => 'sell', 'd' => array('itemId' => $itemId,'amount'=>$amount));
        $this->send($data);
    }

    function createRole(){
        $data = array('a'=>'User','f'=>'createRole','d'=>array('name'=>$this->user));
        $this->send($data);
    }

}

$account = 'test1';
if(isset($argv['1'])){
    $account = 'test'.$argv['1'];
}
$st = new StressTest();
$st->setAccount($account);
$st->start();


//check
//SELECT `user`.uid, sum(item.amount * item.item_id)+`user`.money FROM `item`,`user` WHERE item.uid = `user`.uid group by uid;

