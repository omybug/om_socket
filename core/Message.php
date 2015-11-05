<?php
/**
 * User: omybug
 * Date: 15-11-1 15:54
 */

namespace core;


class Message {
    private $a;
    private $f;
    private $d;
    private $r;

    function __construct($a = '', $f = '', $d = '', $r = '0'){
        $this->a = $a;
        $this->f = $f;
        $this->d = $d;
        $this->r = $r;
    }

    public function setD($d){
        $this->d = $d;
    }

    public function __toString(){
        $data = array('a'=>$this->a,'f'=>$this->f,'d'=>$this->d);
        return json_encode($data);
    }

}