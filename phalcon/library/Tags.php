<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/26
 * Time: 17:08
 */

namespace library;


final class Tags
{
    /**
     * 行为入口
     * User:一根小腿毛；
     * QQ:1368213727
     * @param array $tags
     * @param array $args
     */
    public static function run(array $tags = [],...$args){
        foreach ($tags as $v){
            if(is_array($v)){
                self::run($v,...$args);
            }else{
                self::begin($v,...$args);
            }
        }
    }

    /**
     * 执行
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $path
     * @param mixed ...$args
     */
    private static function begin($path,...$args){
        if(class_exists($path,true)){
            $obj = new $path();
            $obj->run(...$args);
        }else{
            trigger_error("{$path} not Not found ！",E_USER_ERROR);
        }
    }
}