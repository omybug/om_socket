<?php
require_once "../core/Test.php";

class TestLog extends \core\Test{
    public function test(){
        echo "test";
        $this->log->debug("debug");
    }
}

$t = new TestLog();
$t->test();
?>