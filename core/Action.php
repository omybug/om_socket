<?php
/**
 * User: omybug
 * Date: 15-10-5 22:26
 */

namespace core;

class Action {
    protected $serv;
    protected $data;

    public function setServ($serv){
        $this->serv = $serv;
        return $this;
    }

    public function setData($data){
        $this->data = $data;
        return $this;
    }

} 