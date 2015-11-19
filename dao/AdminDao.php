<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/18
 * Time: 13:53
 */

class AdminDao extends \core\Dao{

    public function addStats($data){
        $sql = 'INSERT INTO stats (connection,acceptd,closed,tasking) VALUES (:a1,:a2,:a3,:a4)';
        $this->I($sql, array('a1'=>$data['connection_num'],'a2'=>$data['accept_count'],
            'a3'=>$data['close_count'],'a4'=>$data['tasking_num']));
    }
}