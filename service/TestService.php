<?php
/**
 * User: omybug
 * Date: 15-10-6 14:03
 */

class TestService extends \core\Service{

    private $testDao = null;

    function __construct(){
        $this->testDao = new TestDao();
    }

    public function add($name){
        return $this->testDao->insert($name);
    }

    public function edit($id, $name){
        return $this->testDao->update($id, $name);
    }

    public function del($id){
        return $this->testDao->delete($id);
    }

    public function getAll(){
        return $this->testDao->selectAll();
    }

    public function getOne($id){
        return $this->testDao->select($id);
    }

}