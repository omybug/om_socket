<?php
/**
 * User: omybug
 * Date: 15-11-17 13:29
 */

require_once 'ClientBase.php';

class StressTest extends ClientBase{

    private $mark = 0;
    private $user;
    private $st = 0;

    function connect(){
        $this->login();
    }

    function receive($data){
        $msg = json_decode($data,true);
        if($msg['a'] == 'Shop'){
            echo ($this->timestamp() - $this->st).PHP_EOL;
        }
        if($msg['f'] == 'login'){
            $this->createRole();
        }
        if($msg['f'] == 'createRole'){
            $this->buy();
        }
        sleep(1);
        if($msg['a'] == 'Shop'){
            if($this->mark < 20){
                $this->buy();
            }else{
                if(rand(1,2) == 1){
                    $this->buy();
                }else{
                    $this->sell();
                }
            }
            $this->mark = $this->mark + 1;
            $this->st = $this->timestamp();
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
        echo 'buy ';
    }

    function sell(){
        $amount = rand(1,10);
        $itemId = rand(1,10);
        $data = array('a' => 'Shop', 'f' => 'sell', 'd' => array('itemId' => $itemId,'amount'=>$amount));
        $this->send($data);
        echo 'sell ';
    }

    function createRole(){
        $data = array('a'=>'User','f'=>'createRole','d'=>array('name'=>$this->user));
        $this->send($data);
    }

    function timestamp() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }

}

$account = 'test1';
if(isset($argv['1'])){
    $account = 'test'.$argv['1'];
}
$st = new StressTest();
$st->setAccount($account);
$st->start();
echo 'start';

//check
//SELECT `user`.uid, sum(item.amount * item.item_id)+`user`.money FROM `item`,`user` WHERE item.uid = `user`.uid group by uid;

