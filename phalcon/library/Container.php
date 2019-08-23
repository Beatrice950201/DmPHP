<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/30
 * Time: 10:06
 */

namespace library;

use ReflectionClass;
use ReflectionException;

class Container
{
    /**
     * 容器中的对象实例
     * @var array
     */
    protected $instances = [];

    /**
     * 当前容器对象实例
     * @var Container
     */
    protected static $instance;

    /**
     * 内核特殊处理
     * User:一根小腿毛；
     * QQ:1368213727
     * @var array
     */
    protected static $alias_names=[
        "library\query\Database"
    ];

    /**
     * 获取当前容器的实例（单例）
     * @access public
     * @return static
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * 获取容器标识别名
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $abstract
     * @param $vars
     * @return mixed
     */
    public static function alias($abstract,$vars){
      if(in_array($abstract,self::$alias_names) && isset($vars["alias"]) && $vars["alias"]){
          $abstract = $vars["alias"];unset($vars["alias"]);
          $vars = array_values($vars);
      }
      return [$abstract,$vars];
    }


    /**
     *
     * User:一根小腿毛；
     * QQ:1368213727
     * @access public
     * @param  string        $abstract       类名或者标识
     * @param  array|true    $vars           变量
     * @param  bool          $newInstance    是否每次创建新的实例
     * @return object
     */
    public function make($abstract, $vars = [], $newInstance = false)
    {
        list($names,$vars) = self::alias($abstract,$vars);
        if (isset($this->instances[$names]) && !$newInstance) {
            return $this->instances[$names];
        }
        $object = $this->invokeClass($abstract, $vars);
        if (!$newInstance) {
            $this->instances[$names] = $object;
        }
        return $object;
    }

    /**
     * 调用反射执行类的实例化 支持依赖注入
     * @access public
     * @param  string    $class 类名
     * @param  array     $vars  参数
     * @return mixed
     */
    public function invokeClass($class, $vars = [])
    {
        try {
            $reflect = new ReflectionClass($class);
            return $reflect->newInstanceArgs($vars);
        } catch (ReflectionException $e) {
            trigger_error('class not exists: ' . $class, $class);
        }
    }
}