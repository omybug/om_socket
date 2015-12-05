<?php
/**
 * User: omybug
 * Date: 15-11-1 15:54
 */

namespace core;


class Message
{
    private $c;
    private $d;
    private $r;

    function __construct($c = '' , $d = '' , $r = 1){
        $this->c = $c;
        $this->d = $d;
        $this->r = $r;
    }

//    public function succ($msg = null){
//        if(!empty($msg)){
//            $this->d = array('msg'=>$msg);
//        }
//        $this->r = 1;
//    }

    public function fail($msg = null){
        if(!empty($msg)){
            $this->d = array('msg'=>$msg);
        }
        $this->r = -1;
    }

    public function setMsg($msg){
        $this->d = array('msg'=>$msg);
    }

    public function __toString(){
        $data = array('r'=>$this->r , 'c'=>$this->c , 'd'=>$this->d);
        return json_encode($data).Config::PACKAGE_EOF;
    }

}