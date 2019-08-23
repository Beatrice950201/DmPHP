<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/29
 * Time: 15:02
 */

namespace library;

use Phalcon\Logger\Adapter\File as FileAdapter;

class Log
{

    /**
     * 写入错误
     *User:一根小腿毛；
     *QQ:1368213727
     * @param $msg
     */
    static public function error($msg){
        $log_file = 'error_' . date('Y_m_d') . '.log';
        $logger = new FileAdapter(self::create_folder().$log_file);
        $logger->error($msg."\r\n");
    }

    /**
     * 成功日志
     *User:一根小腿毛；
     *QQ:1368213727
     * @param $msg
     */
    static public function success($msg){
        self::custom("success",$msg);
    }

    /**
     * 警告错误
     *User:一根小腿毛；
     *QQ:1368213727
     * @param $msg
     */
    static public function warning($msg){
        self::custom("warning",$msg);
    }

    /**创建目录
     *User:一根小腿毛；
     *QQ:1368213727
     */
    static private function create_folder(){
        $log_path = BASE_PATH . DS . "runtime" . DS . "log" . DS;
        if(!is_dir($log_path)){
            Dir::make_dir($log_path);
        }
        return $log_path;
    }

    /**
     * 自定义文件
     * User:一根小腿毛；
     * QQ:1368213727
     * @param string $name
     * @param string $message
     */
    static public function custom( string $name,string $message){
        $log_file = $name."_" . date('Y_m_d') . '.log';
        $logger = new FileAdapter(self::create_folder().$log_file);
        $logger->info($message."\r\n");
    }
}