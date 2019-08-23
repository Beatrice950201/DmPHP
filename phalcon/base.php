<?php

final class Phalcon{

    /**
     * 接管的命名空间
     * User:一根小腿毛；
     * QQ:1368213727
     * @var array
     */
    private static $core_namespaces = [
        'library' => OS_PATH,
        'library\facade' => OS_PATH.DS."facade",
        'library\query' => OS_PATH.DS."query",
    ];

    /**
     * 初始化
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public static function run(){
        /***************************************************常量配置**************************************/
        define('CORE_PATH'  , dirname(str_replace('\\', '/' , __FILE__)));
        define('IS_WIN'     , strpos(PHP_OS, 'WIN') !== false);
        define('DS'         ,                              DIRECTORY_SEPARATOR);
        define('BASE_PATH'  ,                           dirname(__DIR__));
        define('OS_PATH'    ,                         CORE_PATH. DS ."library");
        define('EXT'        ,                                           '.php');
        define('APP_PATH'   ,                   BASE_PATH . DS . 'application');
        define('CACHE_DIR'  ,                       BASE_PATH . DS . 'runtime');
        define('VENDOR_PATH',                        BASE_PATH . DS . 'vendor');
        define('ADDONS_PATH',                        BASE_PATH . DS . 'addons');
        /***************************************************常量配置END**************************************/
        /***************************************************自动注册类**************************************/
        spl_autoload_register(function ($class){
            $dir_name = dirname($class);$base_name = basename($class);
            if(isset(self::$core_namespaces[$dir_name])){
                include self::$core_namespaces[$dir_name].DS.$base_name.EXT;
            }
        }, true, true);
        /***************************************************自动注册类END**************************************/
        /***************************************************加载执行文件**************************************/
        library\Run::run();
        /***************************************************加载执行文件END**************************************/
    }

}
Phalcon::run();