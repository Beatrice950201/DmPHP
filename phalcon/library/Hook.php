<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/8/12
 * Time: 11:19
 */

namespace library;


use Phalcon\Di;

class Hook
{
    /**
     * 钩子执行方法
     * User:一根小腿毛；
     * QQ:1368213727
     * @param string $name
     * @param string $class
     * @return mixed
     */
    public function execute(string $name,string $class = ""){
        $hooks = [];
        if(DI::getDefault()->has("hooks")){
            $hooks = DI::getDefault()->get('hooks');
        }
        if(isset($hooks[$name])){
            $class = $hooks[$name];
        }
        $object_class = new $class();
        return $object_class->$name();
    }



}