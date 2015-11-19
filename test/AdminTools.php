<?php
/**
 * User: omybug
 * Date: 15-11-12 21:00
 */
define('TOKEN','000000');

require_once 'ClientBase.php';

class AdminTools extends TestBase{

    private $db;

    function connect(){
        $this->db = \core\DB::instance(array('host'=>'127.0.0.1', 'user'=>'root', 'password'=>'000000','dbname'=>'test'));
        $this->monitor();
    }

    function receive($data){
        $msg = json_decode($data,true);
        sleep(5);
        $this->monitor();
    }

    function reload(){
        $data = array('a'=>'Admin','f'=>'reload','d'=>array('token'=>TOKEN));
        $this->send($data);
    }

    function publish(){
        //发公共消息
        $data = array('a'=>'Chat', 'f'=>'publish','d'=>array('msg'=>'test'));
        $this->send($data);
    }

    function monitor(){
        $data = array('a'=>'Admin', 'f'=>'getOnline');
        $this->send($data);
    }
}

$at = new AdminTools();
$at->start();