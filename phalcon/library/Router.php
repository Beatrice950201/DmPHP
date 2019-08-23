<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/26
 * Time: 15:28
 */

namespace library;

use Phalcon\Di\FactoryDefault;

class Router extends  FactoryDefault
{
    /**
     * 初始化路由
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public static function run(){
        self::base();
    }

    /**
     * 写入路由
     * User:一根小腿毛；
     * QQ:1368213727
     * @param array $route
     */
    public static function route(array $route){
      foreach ($route as $k=>$v){
          if(isset($v["rule"])){
              $rule = $v["rule"]; unset($v["rule"]);
              if(!isset($v["module"])){
                  $module = self::getDefault()->getDispatcher()->getModuleName();
                  $v["module"] = $module ? $module : config("app.default_module");
              }
              $v["namespace"] = "app\\".$v["module"]."\\controllers";
              if(is_int($k)){
                  self::getDefault()->getRouter()->add($rule,$v);
              }else{
                  self::getDefault()->getRouter()->add($rule,$v)->setName($k);
              }
          }
      }
    }
    /**
     * 注册基础路由
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function base(){
        $route = APP_PATH.DS."route".EXT;
        if(is_file($route)){
            $route = include $route;
            self::route($route);
        }
    }

    /**
     * url 主函数
     * User:一根小腿毛；
     * QQ:1368213727
     * @param string $url
     * @param array $opt
     * @return
     */
    public static function url(string $url,array $opt = []){
      $is_name = self::matching($url,$opt);
      $router_str = self::getDefault()->getUrl()->get($url,$opt);
      if($is_name){
          $router = ["for"=>$is_name];
          foreach ($opt as $k=>$vs){
              $router[$k] = $vs;
          }
          $router_str = self::getDefault()->getUrl()->get($router);
      }
      return $router_str;
    }

    /**
     * 获取所有能被匹配的路由
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function routes_array($opt):array {
        $routes = self::getDefault()->getRouter()->getRoutes();
        $routes_array = [];
        foreach ($routes as $k=>$v){
            if($v->getName()){
                $path = $v->getPaths();
                foreach ($opt as $key=>$val){
                    unset($path[$key]);
                }
                $routes_array[] = ["name"=>$v->getName(), "path"=>$path];
            }
        }
        return $routes_array;
    }

    /**
     * 组合路由
     * User:一根小腿毛；
     * QQ:1368213727
     * @param string $url
     * @param array $opt
     * @return array
     */
    private static function combination_route(string $url){
      list($module,$controller,$action) = explode("/",$url);
      $route = [];
      $route["controller"] = $controller;
      $route["action"] = $action;
      $route["module"] = $module;
      $route["namespace"] = "app\\{$module}\\controllers";
      return $route;
    }

    /**
     * 是否匹配
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $url
     * @param $opt
     * @return bool
     */
    private static function matching($url,$opt){
        $this_route = self::combination_route($url);
        $routes = self::routes_array($opt);
        $_is = false;
        foreach ($routes as $key => $val)
        {
           $res = array_merge(array_diff($val["path"],$this_route),array_diff($this_route,$val["path"]));
           if(count($res) === 0){
               $_is =  $val["name"];
               break;
           }
        }
        return $_is;
    }

}