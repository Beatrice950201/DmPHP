<?php
/**
 * User: 一根小腿毛@qq：1368213727
 * Created by PhpStorm.
 * User: windo
 * Date: 2019/3/4
 * Time: 20:35
 */

namespace library;

use Phalcon\Cli\Dispatcher;
use Phalcon\Di\FactoryDefault\Cli;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;

class CliService extends Cli
{

    private static $application;
    /**
     * 初始化
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public static function run(){
      self::dispatcher();
      self::loaders();
      self::application();
      self::filter();
    }

    /**
     * 调度开始
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function dispatcher(){
        self::getDefault()->setShared('dispatcher',function(){
            $eventsManager = new EventsManager();
            $eventsManager->attach(
                "dispatch:beforeDispatchLoop",
                function ($event, $dispatcher){
                    $tags = ["library\\Begin"];
                    if(is_array(config("tags.app_dispatch"))){
                        $tags = array_merge($tags,config("tags.app_dispatch"));
                    }
                    Tags::run($tags,$dispatcher,self::getDefault());
                }
            );
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace("app\\".config('app.default_cli_module')."\\tasks");
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        });
    }

    /**
     * 加载命名空间等等
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function loaders(){
        self::getDefault()->setShared('loader',function (){
            return  new Loader();
        });
        Register::run();
    }

    /**
     * 启动app
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function application(){
        $default_cli_module = config("app.default_cli_module");
        self::$application = new ConsoleApp(self::getDefault());
        self::$application->registerModules([
            $default_cli_module => [
                'className' =>  "app\\".$default_cli_module .'\\module'
            ]
        ]);
    }

    /**
     * 输出
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function filter(){
        $arguments = self::arguments();
        if(config("tags.view_filter")){
            Tags::run(config("tags.view_filter"),self::$application,$arguments,self::getDefault());
        }else{
            self::$application->handle($arguments);
        }
    }

    /**
     * 处理参数
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function arguments(){
        $arguments = [
            'module' => config("app.default_cli_module")
        ];
        $argv = $_SERVER['argv'];
        foreach ($argv as $k => $arg) {
            if ($k == 1) {
                $arguments['task'] = $arg;
            } elseif ($k == 2) {
                $arguments['action'] = $arg;
            } elseif ($k >= 3) {
                $arguments['params'][] = $arg;
            }
        }
        return $arguments;
    }
}