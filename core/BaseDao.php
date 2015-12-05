<?php
/**
 * User: omybug
 * Date: 15-2-12 11:32
 */

namespace core;

class BaseDao {
    /**
     * @var DB
     */
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

    protected function S($query, array $params = null){
        return $this->db->query($query, $params);
    }

    protected function single($query, array $params = null){
        return $this->db->single($query, $params);
    }

    protected function column($query, array $params = null){
        return $this->db->column($query, $params);
    }

    protected function U($query,array $params){
//        if(!stristr($query, 'where') || empty($params)){
//            Log::error($query.' has no where condition！');
//            return false;
//        }
        return $this->db->update($query, $params);
    }


    protected function D($query,array $params){
        if(!stristr($query, 'where') || empty($params)){
            Log::error($query.' has no where condition！');
            return false;
        }
        return $this->db->delete($query,$params);
    }

    protected function I($query,array $params = null){
        return $this->db->insert($query,$params);
    }

    public function openProfiles(){
        return $this->S('set profiling=1;');
    }

    public function showProfiles(){
        return $this->S('show profiles;');
    }
}