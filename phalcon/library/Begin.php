<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/26
 * Time: 17:33
 */

namespace library;


use Phalcon\Di\FactoryDefault;
use Phalcon\Loader AS OsLoader;

class Begin extends FactoryDefault
{

    /**
     * 初始化函数
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $dispatcher
     */
    public function run($dispatcher){
      self::helpers($dispatcher);
      self::config($dispatcher);
      if(APP_CLI === "WEB"){
          self::router($dispatcher);
          self::params($dispatcher);
      }
    }

    /**
     * 加载用户函数
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $dispatcher
     */
    private static function helpers($dispatcher){
        $files  = [];
        $files[] = APP_PATH.DS."common".EXT;
        $files[] = APP_PATH.DS.self::module($dispatcher).DS."common".EXT;
        $loaders = new OsLoader();
        $loaders->registerFiles($files);
        $loaders->register();
    }

    /**
     * 加载用户配置
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $dispatcher
     */
    private static function config($dispatcher){
        $module = self::module($dispatcher);
        $config = APP_PATH.DS.$module.DS."config".EXT;
        if(is_file($config) && is_array(include $config)){
           config($module,include $config);
        }
    }

    /**
     * 加载用户路由
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $dispatcher
     */
    private static function router($dispatcher){
        $module = $dispatcher->getModuleName();
        $route = APP_PATH.DS.$module.DS."route".EXT;
        if(is_file($route)){
            $route = include $route;
            Router::route($route);
        }
    }

    /**
     * 加载模板函数
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $dispatcher
     * @param $compiler
     */
    public static function template_func($dispatcher,$compiler){
      $files = [
          APP_PATH.DS."template".EXT,
          APP_PATH.DS.self::module($dispatcher).DS."template".EXT,
      ];
      $volt = new Compiler();
      $array = [];
      foreach ($files as $file){
          if(is_file($file)){
             $func = include $file;
             foreach ($func as $value){
                 if(!in_array($value,$array)){
                     $array[] = $value;
                 }
             }
          }
      }
      $volt->run($array,$compiler);
    }

    /**
     * 处理参数问题
     * @param $dispatcher
     */
    private static function params($dispatcher){
        $get = self::getDefault()->getRequest()->getQuery();unset($get['_url']);
        $param = $dispatcher->getParams();
        $getParams = [];
        foreach ($param as $key => $value) {
            if(!is_int($key)){
                $getParams[$key] = $value;
                continue;
            }
            if ($key % 2 != 0) {
                $getParams[$param[$key - 1]] = $value;
            } else {
                $getParams[$value] = false;
            }
        }
        $getParams = array_merge($getParams,$get);
        $dispatcher->setParams($getParams);
    }

    /**
     * 获取模块名
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $dispatcher
     * @return bool
     */
    private static function module($dispatcher){
        if(APP_CLI === "CLI"){
            $name =  config("app.default_cli_module");
        }else{
            $name =  $dispatcher->getModuleName();
        }
        return $name;
    }
}