<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/30
 * Time: 10:10
 */

namespace library\facade;

use library\Facade;
use Phalcon\Mvc\Model;
use library\query\Database AS PackageDatabase;
/**
 * Class PackageDatabase
 * @package query
 * @mixin PackageDatabase
 * @method PackageDatabase where(array $where) static 查询条件
 * @method PackageDatabase field(array $field) static 指定查询字段
 * @method PackageDatabase limit(array $limit) static 查询LIMIT
 * @method PackageDatabase order(string $limit) static 查询ORDER
 * @method PackageDatabase cache(bool $cache) static 设置查询缓存
 * @method PackageDatabase find() static 查询单个记录
 * @method PackageDatabase select() static 查询多个记录
 */

class Database extends Model
{
    public static $error;

    /**
     * 构造改装
     * User:一根小腿毛；
     * QQ:1368213727
     */

    public function initialize(){
        $table_name = self::init_table(static::class);
        $this->setSource($table_name);
    }

    /**
     * 捕捉静态
     * User:一根小腿毛；
     * QQ:1368213727
     * @param string $method
     * @param mixed $params
     * @return PackageDatabase
     */
    public static function __callStatic($method, $params)
    {
        return self::call_func($method, $params);
    }

    /**
     * 重定向动态
     * User:一根小腿毛；
     * QQ:1368213727
     * @param string $method
     * @param mixed $params
     * @return PackageDatabase
     */
    function __call($method, $params)
    {
        return self::call_func($method, $params);
    }

    /**
     * 启动门面
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $method
     * @param $params
     * @return PackageDatabase
     */
    private static function call_func($method, $params){
        return call_user_func_array([
            Facade::createFacade(
                "library\query\Database",
                [
                    "alias"=>static::class ,
                    "model"=>new static()
                ],
                true
            ), $method], $params);
    }

    /**
     * 初始化表模型
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $class
     * @return bool|string
     */
    private static function init_table($class)
    {
        $name = config("database.prefix");
        if(property_exists($class,"table_name") && $class::$table_name){
            $name .= $class::$table_name;
        }else{
            $name .= to_under_score($class);
        }
        return $name;
    }

}