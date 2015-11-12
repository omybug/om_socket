<?php
/**
 * User: omybug
 * Date: 15-2-12 11:32
 */

namespace core;

class Dao {
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

    /**
     * @param $query
     * @param null $params
     * @return mixed
     */
    protected function update($query,$params){
        if(!stristr($query, 'where') || empty($params)){
            Log::error($query.' has no where condition！');
            return false;
        }
        return $this->db->update($query, $params);
    }


    /**
     * @param $query
     * @param null $params
     * @return mixed
     */
    protected function delete($query,$params){
        if(!stristr($query, 'where') || empty($params)){
            Log::error($query.' has no where condition！');
            return false;
        }
        return $this->db->delete($query,$params);
    }

    /**
     * @param $query
     * @param null $params
     * @return mixed
     */
    protected function insert($query,$params = null){
        return $this->db->insert($query,$params);
    }
}