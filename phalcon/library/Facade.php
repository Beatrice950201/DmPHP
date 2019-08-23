<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/30
 * Time: 9:16
 */

namespace library;


class Facade
{

    /**
     * 绑定对象
     * @var array
     */
    protected static $bind = [];

    /**
     * 始终创建新的对象实例
     * @var bool
     */
    protected static $alwaysNewInstance;

    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass(){}


    /**
     * 创建Facade实例
     * @static
     * @access protected
     * @param  string    $class          类名或标识
     * @param  array     $args           变量
     * @param  bool      $newInstance    是否每次创建新的实例
     * @return object
     */
    public static function createFacade($class = '', $args = [], $newInstance = false)
    {
        $class = $class ?: static::class;
        $facadeClass = static::getFacadeClass();

        if ($facadeClass) {
            $class = $facadeClass;
        } elseif (isset(self::$bind[$class])) {
            $class = self::$bind[$class];
        }
        if (static::$alwaysNewInstance) {
            $newInstance = true;
        }
        return Container::getInstance()->make($class, $args, $newInstance);
    }

    /**
     * 捕捉
     * User:一根小腿毛；
     * QQ:1368213727

     */
    public static function __callStatic($method, $params)
    {
        return call_user_func_array([static::createFacade(), $method], $params);
    }

}