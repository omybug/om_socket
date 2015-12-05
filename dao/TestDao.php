<?php
/**
 * User: omybug
 * Date: 15-10-6 14:02
 */

namespace dao;

class TestDao extends Dao{
    /**
     * @param $id int
     * @return array
     */
    public function select($id){
//        $result   = $this->db->query("SELECT * FROM test");
//        return $result;
//        $this->db->bind("id","1");
//        $this->db->bind("name","Tony");
//        $result   =  $this->db->query("SELECT * FROM test WHERE name = :name AND id = :id");
//        $this->db->bindMore(array("name"=>"Tony","id"=>"1"));
//        $result   =  $this->db->query("SELECT * FROM test WHERE name = :name AND id = :id");
        return  $this->db->row("SELECT * FROM test WHERE  id = :id", array("id"=>$id));
//        $this->db->bind("id","1");
//        $result = $this->db->single("SELECT name FROM test WHERE id = :id");
//        $result = $this->db->column("SELECT name FROM test");
    }


    /**
     * @return mixed
     */
    public function selectAll(){
        $result = $this->db->query("SELECT * FROM test;");
        return $result;
    }


    /**
     * @param $name string
     * @return mixed
     */
    public function insert($name){
        return $this->db->query("INSERT INTO test(name) VALUES(:f)", array("f"=>$name));
    }

    /**
     * @param $id int
     * @param $name string
     * @return mixed
     */
    public function update($id, $name){
        return $result = $this->db->query("UPDATE test SET name = :f WHERE id = :id", array("f"=>$name,"id"=>$id));

    }

    /**
     * @param $id int
     * @return mixed
     */
    public function delete($id){
        return $this->db->query("DELETE FROM test WHERE id = :id", array("id"=>$id));
    }
}