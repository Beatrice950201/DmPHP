<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/25
 * Time: 17:41
 */

namespace library;

use Phalcon\Di\FactoryDefault;

class Errors extends FactoryDefault
{
    private static $config;

    /**
     * 接盘错误
     *User:一根小腿毛；
     *QQ:1368213727
     */
    public static function run(){
        self::$config = self::getDefault()->getConfig()->toArray();
        error_reporting(0);
        set_error_handler(array(__CLASS__,'error'));//警告错误处理
        register_shutdown_function(array(__CLASS__,'fatal_error'));//致命错误处理
    }

    /**
     * 普通错误
     *User:一根小腿毛；
     *QQ:1368213727
     * @param $e
     * @param $error
     * @param $file
     * @param $line
     */
    public static function error($e, $error, $file, $line){
        if(isset(self::$config["tags"]["log_write"]) && self::$config["tags"]["log_write"]){
            Tags::run(self::$config["tags"]["log_write"],$e, $error, $file, $line);
        }else{
            if(APP_CLI === "WEB"){
               self::template($error,$file,$line);
            }else{
               self::console($error,$file,$line);
            }
            Log::error("{$error} in {$file} line {$line}");
            die();
        }
    }

    /**
     * web 错误处理
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $error
     * @param $file
     * @param $line
     */
    private static function template($error,$file,$line){
        if(self::$config["app"]["app_debug"]){
            $template = CORE_PATH. DS ."template" . DS . "halt.html";
            $e = self::backtrace() ;
            $e["message"] = $error;
            $e["file"] = $file;
            $e["line"] = $line;
            if(function_exists("get_last_sql")){
              $e["sql"] = get_last_sql();
            }
        }else{
            $e = [];
            $template = self::$config["app"]["error_template"];
            $e["message"] = self::$config["app"]["error_message"];
            $e["jumpUrl"] = "/";
            $e["waitSecond"] = 5;
        }
        include $template;
    }

    /**
     * 命令行模式处理
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $error
     * @param $file
     * @param $line
     */
    private static function console($error,$file,$line){
        echo "\"{$error} in {$file} line {$line}\"\r\n";
    }

    /**
     * 捕捉错误
     * User:一根小腿毛；
     * QQ:1368213727
     * @param array $e
     * @return array
     */
    public static function backtrace(array $e = []){
        $trace = debug_backtrace();
        $e['class'] = isset($trace[0]['class']) ? $trace[0]['class'] : '';
        $e['function'] = isset($trace[0]['function']) ? $trace[0]['function'] : '';
        ob_start();
        debug_print_backtrace();
        $e['trace'] = htmlspecialchars(ob_get_clean());
        return $e;
    }
    /**
     * 致命错误
     *User:一根小腿毛；
     *QQ:1368213727
     */
    public static function fatal_error(){
        if($e = error_get_last()){
            self::error($e['type'],$e['message'],$e['file'],$e['line']);
        }
    }

}