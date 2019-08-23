<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/8/12
 * Time: 11:19
 */

use library\Hook as PackageHook;

/**
 * Class PackageHook
 * @package query
 * @mixin PackageHook
 * @method PackageHook execute(string $name) static 执行钩子
 */

namespace library\facade;

use library\Facade;

class Hook extends Facade
{
    protected  static function getFacadeClass()
    {
        return "library\\Hook";
    }

}