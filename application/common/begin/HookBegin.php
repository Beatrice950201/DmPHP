<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/8/23
 * Time: 14:34
 */

namespace app\common\begin;

use Phalcon\Di\FactoryDefault;

class HookBegin extends FactoryDefault
{

    /**
     * 行为入口文件（绑定钩子）
     * User:一根小腿毛；
     * QQ:1368213727
     * @return string
     */
    public function run()
    {
        self::getDefault()->setShared('hooks', function () {
            return [
                "test_hook"=>"app\\common\\hooks\\Test" // 绑定钩子
            ];
        });
    }

}
