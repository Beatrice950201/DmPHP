<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/29
 * Time: 11:30
 */

namespace library;

class Compiler
{
    public function run($array,$compiler){
        foreach ($array as $value) {
            if (function_exists($value)) {
                $compiler->addFunction(
                    $value,
                    function ( $resolvedArgs )use ($value) {
                        return $value . "(". $resolvedArgs . ")";
                    }
                );
            }
        }
    }
}