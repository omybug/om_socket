<?php
/**
 * User: omybug
 * Date: 15-10-5 22:28
 */

namespace controller;

use core\Log;
use service\TestService;

class TestController extends Controller{

    private $testService;

    function __construct(){
        parent::__construct();
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
        Log::debug(time());
    }

    public function hook1(){
//        Log::debug('hook1');
    }

    public function hook2(){
//        Log::debug('hook2');
    }
}