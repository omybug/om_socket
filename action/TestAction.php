<?php
/**
 * User: omybug
 * Date: 15-10-5 22:28
 */

class TestAction extends \core\Action{

    private $testService;

    function __construct(){
        $this->testService = new TestService();
    }

    public function test(){
        echo "test\n";
    }

    public function select(){
        var_dump($this->data);
    }

    public function token(){
        var_dump($this->data);
    }

    public function tick(){
        \core\Log::debug(time());
    }
}