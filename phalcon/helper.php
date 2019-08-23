<?php

use library\Convention;
use library\Router;
use Phalcon\Di;

/**
 * 读写配置
 * User:一根小腿毛；
 * QQ:1368213727
 * @param string $name
 * @param string $value
 * @return bool
 */
function config( string $name = "", $value = null){
    if($name && !$value || !$name && !$value){
        return Convention::acquire($name);// 获取配置
    }
    if($name && $value || !$name && $value){
        if($name && $value){
            $value = [$name=>$value];
        }
        return Convention::write($value);// 写入配置
    }
    return null;
}

/**
 * 控制器是否否在
 * User:一根小腿毛；
 * QQ:1368213727
 * @param string $namespace
 * @param string $name
 * @return bool
 */
function is_class_controller(string $namespace,string $name):bool {
  $path = $namespace.DS.$name."Controller";
  return class_exists($path);
}

/**
 * 链接生成主函数 todo 不在支持缺省值传参
 * User:一根小腿毛；
 * QQ:1368213727
 * @param string $url
 * @param array $opt
 * @return string
 */
function url(string $url,array $opt = []):string {
   return Router::url($url,$opt);
}

/**
 * 驼峰转下划线
 * @param $str
 * @return string
 */
function to_under_score($str)
{
    $str = str_replace('\\','/',$str);
    $str = basename($str);
    $res = preg_replace_callback('/([A-Z]+)/',function($match)
    {
        return '_'.strtolower($match[0]);
    },$str);
    return trim(preg_replace('/_{2,}/','_',$res),'_');
}

/**
 * 获取最后SQL
 * User:一根小腿毛；
 * QQ:1368213727
 * @return mixed
 */
function get_last_sql()
{
    try {
        $di = Phalcon\DI::getDefault()->getProfiler();
        return $di->getLastProfile()->getSQLStatement();
    } finally {
        return "Failed to obtain database operation record!";
    }
}

/**
 * session操作
 * User:一根小腿毛；
 * QQ:1368213727
 * @param string $name
 * @param null $value
 * @return mixed |null
 */
function session(string $name,$value = null)
{
    $session = Phalcon\DI::getDefault()->get("session");
    if($value === null){
        $value = $session->get($name);
    }else{
        $session->set($name,$value);
        $value = true;
    }
    return $value;
}

/**
 * 删除session
 * User:一根小腿毛；
 * QQ:1368213727
 * @param string $name
 * @return bool
 */
function session_remove(string $name):bool
{
    Phalcon\DI::getDefault()->get("session")->remove($name);
    return true;
}

/**
 * cookie 操作
 * User:一根小腿毛；
 * QQ:1368213727
 * @param string $name
 * @param null $value
 * @param int $time 单位/天
 * @return bool|null
 */
function cookies(string $name,$value = null,int $time = 7)
{
    $cookies = Phalcon\DI::getDefault()->get("cookies");
    if($value === null){
        $value = $cookies->get($name)->getValue();
    }else{
        $cookies->set($name,$value,time() + $time * 86400);
        $value = true;
    }
    return $value;
}

/**
 * 删除cookies
 * User:一根小腿毛；
 * QQ:1368213727
 * @param string $name
 * @return bool
 */
function cookies_remove(string $name):bool
{
    Phalcon\DI::getDefault()->get("cookies")->remove($name);
    return true;
}

/**
 * 删除缓存
 * User:一根小腿毛；
 * QQ:1368213727
 * @param string $name
 * @return bool
 */
function cache_remove(string $name):bool
{
    Phalcon\DI::getDefault()->get("modelsCache")->delete($name);
    return true;
}

/**
 * 缓存操作
 * User:一根小腿毛；
 * QQ:1368213727
 * @param string $name
 * @param string $value
 * @param int|null $time
 * @return bool
 */
function cache(string $name, $value = null, int $time = null)
{
    $cache = Phalcon\DI::getDefault()->get('modelsCache');
    if($value === null){
        $value = $cache->get($name);
    }else{
        $cache->set($name,$value,$time ?? config("app.cache_time"));
        $value = true;
    }
    return $value;
}

/**
 * 重定向跳转
 * User:一根小腿毛；
 * QQ:1368213727
 * @param string $url
 */
function redirect(string $url)
{
    $response = Phalcon\DI::getDefault()->get("response");
    $response->redirect($url);
    $response->send();
    exit();
}
