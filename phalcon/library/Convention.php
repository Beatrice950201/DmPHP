<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/25
 * Time: 17:00
 */

namespace library;


use Phalcon\Config;
use Phalcon\Di\FactoryDefault;

class Convention extends FactoryDefault
{
    private static $os = [
        CORE_PATH.DS.'convention'.EXT,
        APP_PATH . DS . 'tags'.EXT,
        BASE_PATH . DS . "config"
    ];

    /**
     * 获取主配置文件
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public static function os():array {
        $list_file = [];
        foreach (self::$os as $v){
            if(substr($v, -4) != EXT){
                $list = Dir::list_dir($v);
               foreach ($list as $file_name){
                   if(substr($file_name, -4) == EXT){
                       $list_file[] = $v.DS.$file_name;
                   }
               }
            }else{
                $list_file[] = $v;
            }
        }
        return self::array($list_file);
    }

    /**
     * 获取数组(合并以后的数组)
     * User:一根小腿毛；
     * QQ:1368213727
     * @param array $array
     * @return array
     */
    public static function array( array $array):array {
      $_array = [];
      foreach ($array as $v){
          if(!is_file($v)){
              continue;
          }
         if($v === self::$os[0]){
             $_array = include $v;
         }else{
             $key_name = str_replace(EXT,"",basename($v));
             if(isset($_array[$key_name])){
                 $values = include $v;
                 is_array($values) && $_array[$key_name] = array_merge($_array[$key_name],$values);
             }else{
                 $_array[$key_name] = include $v;
             }
         }
      }
      return $_array;
    }

    /**
     * 获取配置
     * User:一根小腿毛；
     * QQ:1368213727
     * @param string $name
     * @return |null
     */
    public static function acquire( string $name){
      $name = array_filter(explode(".",trim($name,".")));
      $config = self::getDefault()->getConfig()->toArray();
      foreach ($name as $v){
          $config =  isset($config[$v]) ? $config[$v] : null;
      }
      return $config;
    }

    /**
     * 写入配置
     * User:一根小腿毛；
     * QQ:1368213727
     * @param array $value
     * @return bool
     */
    public static function write(array $value){
        $_config = self::getDefault()->getConfig();
        $_config->merge(new Config($value));
        return self::getDefault()->setConfig($_config);
    }



}