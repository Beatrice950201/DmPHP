<?php
/**
 * User: 一根小腿毛@qq：1368213727
 * Created by PhpStorm.
 * User: windo
 * Date: 2019/3/4
 * Time: 20:35
 */

namespace library;

use Phalcon\Di\FactoryDefault;
use \Phalcon\Mvc\Dispatcher as PhDispatcher;
use Phalcon\Loader;
use Phalcon\Mvc\Router AS CRouter;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Application;
use Phalcon\Http\Response\Cookies;
use Phalcon\Crypt;

class WebService extends FactoryDefault
{

    private static $application;

    public static function run(){
      self::router();
      self::baseUrl();
      self::session();
      self::cookies();
      self::crypt();
      self::dispatcher();
      self::loaders();
      self::application();
      Router::run();
      self::filter();
    }

    /**
     * 注册路由
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function router(){
        self::getDefault()->setShared('router', function () {
            $router = new CRouter();
            $default_module = config("app.default_module");
            $router->setDefaultModule($default_module);
            return $router;
        });
    }

    /**
     * 设置BaseUrl
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function baseUrl(){
        $url = new UrlResolver();
        $url->setBaseUri(preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]));
        return $url;
    }

    /**
     * 设置session
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function session(){
        self::getDefault()->setShared('session', function () {
            $session = new SessionAdapter();
            $session->start();
            return $session;
        });
    }

    /**
     * 设置cookies
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function cookies(){
        self::getDefault()->setShared('cookies', function () {
            $cookies =new Cookies();
            $cookies->useEncryption(true);
            return $cookies;
        });
    }

    /**
     * 设置 crypt 加密项
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function crypt(){
        self::getDefault()->setShared('crypt', function () {
            $crypt = new Crypt();
            $crypt->setKey(config("app.crypt_keys"));
            return $crypt;
        });
    }

    /**
     * 设置调度
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function dispatcher(){
        self::getDefault()->setShared('dispatcher',function(){
            $eventsManager = new EventsManager();
            $eventsManager->attach("dispatch:beforeException",
                function ($_, $dispatcher, $exception){
                    if(config("app.app_debug") === false && is_class_controller($dispatcher->getNamespaceName(),config("app.empty_controller"))){
                        switch ($exception->getCode()) {
                            case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                            case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward([
                                'controller' => config("app.empty_controller"),
                                'action'     => config("app.empty_action"),
                            ]);
                            return false;
                        }
                    }
                }
            );
            $eventsManager->attach(
                "dispatch:beforeDispatchLoop",
                function ($_, $dispatcher){
                    $app_dispatch = config("tags.app_dispatch");
                    $tags = ["library\\Begin"];
                    if(is_array($app_dispatch)){
                        $tags = array_merge($tags,$app_dispatch);
                    }
                   Tags::run($tags,$dispatcher,self::getDefault());
                }
            );
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace("app\\".config("app.default_module")."\\controllers");//设置默认访问模块
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
     * User: 一根小腿毛@qq：1368213727
     */
    private static function application(){
       $models = Register::models();
       $application = new Application(self::getDefault());
       $application->registerModules($models);
       self::$application = $application;
    }

    /**
     * 视图输出或过滤
     * User:一根小腿毛；
     * QQ:1368213727
     */
    private static function filter(){
        if(config("tags.view_filter")){
            Tags::run(config("tags.view_filter"),self::$application,self::getDefault());
        }else{
            self::$application->handle()->send();
        }
    }

}