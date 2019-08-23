<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/25
 * Time: 16:35
 */

namespace library;

use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Cache\Backend\Apcu;
use Phalcon\Cache\Backend\File;
use Phalcon\Cache\Frontend\Data;
use Phalcon\Config;
use Phalcon\Db\Profiler;
use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Events\Manager;

class Run extends Di
{


    public static function run(){
        self::di();
        self::config();
        self::error();
        self::timezone();
        self::profiler();
        self::databases();
        self::cache();
        self::volt();
        self::service();
    }

    /**
     * 写入DI
     * User: 一根小腿毛@qq：1368213727
     */
    private static function di(){
        if(APP_CLI === "CLI"){
            $di =  new Cli();
        }else{
            $di =  new FactoryDefault();
        }
       self::setDefault($di);
    }

    /**
     * 写入config
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function config(){
        self::getDefault()->setShared('config', function () {
            return new Config(Convention::os());
        });
    }

    /**
     * 注册错误机制
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function error(){
       Errors::run();
    }

    /**
     * 时区设置
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function timezone(){
        ini_set('date.timezone',self::getDefault()->getConfig()->app->timezone);
    }

    /**
     * 注册监听器
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function profiler(){
        self::getDefault()->setShared('profiler', function () {
            return new Profiler();
        });
    }
    /**
     * 注册DB
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function databases(){
        self::getDefault()->setShared('db', function (){
            $eventsManager = new Manager();
            $profiler = self::getDefault()->getProfiler();
            $eventsManager->attach('db', function($event, $connection) use ($profiler) {
                if ($event->getType() == 'beforeQuery') {
                    $profiler->startProfile($connection->getSQLStatement());
                }
                if ($event->getType() == 'afterQuery') {
                    $profiler->stopProfile();
                }
            });
            $class = 'Phalcon\Db\Adapter\Pdo\\' . config("database.adapter");
            $connection = new $class(config("database"));
            $connection->setEventsManager($eventsManager);
            return $connection;
        });
    }

    /**
     * 缓存
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function cache(){
        self::getDefault()->set('modelsCache',function(){
            $frontCache = new Data([
                "lifetime" => config("app.cache_time")  //全局默认有效时间
            ]);
            if(extension_loaded("apcu") && config("app.cache_type") === "apcu"){
                $cache = new Apcu($frontCache);
            }else{
                $cache_path = CACHE_DIR.DS."cache".DS;(!is_dir($cache_path)) && Dir::make_dir($cache_path);
                $cache = new File($frontCache,["cacheDir" => $cache_path]);
            }
            return $cache;
        });
    }

    /**
     * 模板
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function volt(){
        self::getDefault()->setShared('voltShared', function ($view) {
            $volt = new Volt($view, self::getDefault());
            $volt->setOptions([
                'compileAlways' => config("app.app_debug"),
                'compiledPath' => function($templatePath){
                    $basePath = APP_PATH.DS;
                    if ($basePath && substr($basePath, 0, 2) == '..') {
                        $basePath = dirname(__DIR__);
                    }
                    $basePath = realpath($basePath);
                    $templatePath = trim(substr($templatePath, strlen($basePath)), '\\/');
                    $filename = basename(str_replace(['\\', '/'], '_', $templatePath), '.volt');
                    $cacheDir = CACHE_DIR.DS;
                    if ($cacheDir && substr($cacheDir, 0, 2) == '..') {
                        $cacheDir = __DIR__ . DIRECTORY_SEPARATOR . $cacheDir;
                    }
                    $cacheDir = realpath($cacheDir);
                    if (!$cacheDir) {
                        $cacheDir = sys_get_temp_dir();
                    }
                    if (!is_dir($cacheDir . DIRECTORY_SEPARATOR . 'volt' )) {
                        Dir::make_dir($cacheDir . DIRECTORY_SEPARATOR . 'volt');
                    }
                    return $cacheDir . DIRECTORY_SEPARATOR . 'volt' . DIRECTORY_SEPARATOR . md5($filename).EXT;
                }
            ]);
            Begin::template_func(self::getDefault()->getDispatcher(),$volt->getCompiler());
            return $volt;
        });
    }

    /**
     * 获取分支
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function service(){
        $class = "library\\".ucfirst(strtolower(APP_CLI))."Service";
        $class::run();
    }


}