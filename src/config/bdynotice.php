<?php
return [
    //初始化企微配置 --agent_id 参数一定要是int类型
    'work' => [
        'corp_id' => '',
        'agent_id' => 0,
        'secret' => '',
    ],

    //调试模式
    'sandbox' => env('TEST_NOTICE',true),
];
