<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/26
 * Time: 9:39
 */

namespace library;

use Phalcon\Di\FactoryDefault;

class Register extends FactoryDefault
{
    /**
     * 初始化加载
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public static function run(){
      self::namespaces();
      self::classes();
      self::files();
      self::ends();
    }


    /**
     * 注册命名空间
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public static function namespaces(){
        $namespaces = [];
        $directory = Dir::list_dir(APP_PATH);
        foreach ($directory as $k=>$v){
            $path = APP_PATH.DS.$k;
            if(is_dir($path)&& !is_file($path.DS."module".EXT)){
                $namespaces["app\\{$k}"] = APP_PATH . DS . $k . DS;
            }
        }
        if(is_dir(ADDONS_PATH.DS)){
            $namespaces["addons"] = ADDONS_PATH.DS;
        }
        self::getDefault()->getLoader()->registerNamespaces($namespaces);
    }

    /**
     * 注册分组
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public static function classes(){
      $models = self::models();
      $classes = [];
      foreach ($models as $k=>$v){
          $classes[$v["className"]] = APP_PATH . DS .$k . DS . 'module'.EXT;
      }
        self::getDefault()->getLoader()->registerClasses($classes);
    }

    /**
     * 注册主函数库
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public static function files(){
        $files=[];
        $files[] = CORE_PATH . DS . 'helper'.EXT;
        $files[] = VENDOR_PATH . DS . 'autoload'.EXT;
        self::getDefault()->getLoader()->registerFiles($files);
    }
    /**
     * 实际注册
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public static function ends(){
        self::getDefault()->getLoader()->register();
    }
    /**
     * 获取所有分组
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public static function models(){
        $directory = Dir::list_dir(APP_PATH);
        $models = [];
        foreach ($directory as $k=>$v){
           $path = APP_PATH.DS.$k;
           if(is_dir($path) && is_file($path.DS."module".EXT)){
               $models[$k] = ['className' => 'app\\'.$k.'\\module'];
           }
        }
       return $models;
    }

}