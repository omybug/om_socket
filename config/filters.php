<?php

/**
 * type: 1,在hook之前执行; 2,在hook之后执行
 * hook: 拦截点，同一个拦截点可以注册多个
 * controller: 执行对应的控制器
 */
return [
    ['type'=>'1','hook'=>'ShopController@buy','controller'=>'TestController@hook1'],
    ['type'=>'2','hook'=>'ShopController@buy','controller'=>'TestController@hook2'],
];
