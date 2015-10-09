<?php
/**
 * Created by IntelliJ IDEA.
 * User: omybug
 * Date: 15-2-12
 * Time: 下午11:32
 */

namespace core;

class Dao {

    protected $db;

    function __construct(){
        $this->db = DB::instance();
    }

    public function begin(){
        $this->db->begin();
    }

    public function rollback(){
        $this->db->rollback();
    }

    public function commit(){
        $this->db->commit();
    }

} 