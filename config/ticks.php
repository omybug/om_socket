<?php
/**
 * 定时器,精确到秒
 * type: 1,循环执行，2，每日执行
 * time: 循环时间或每日定点时间（秒），最大不的超过86400s
 *
 */
return [
	//每60秒执行
    array('type'=>1, 'time'=>60, 'controller'=>'TickController@heartbeat'),
    //服务器状态记录
    array('type'=>1, 'time'=>10, 'controller'=>'AdminController@stats'),
    //每日14时定时执行
    array('type'=>2, 'time'=>3600*14,'controller'=>'TickController@announce','d'=>'I Love PHP')
];