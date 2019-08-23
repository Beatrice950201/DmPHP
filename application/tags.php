<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * 行为控制
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/4/26
 * Time: 9:07
 */

return [
    // 应用调度开始
    'app_dispatch' => [
        "app\\common\\begin\\Dispatch",
        "app\\common\\begin\\HookBegin",
    ],
    // 日志写入
    'log_write'    => [],
    // 视图内容过滤
    'view_filter'  => []
];
