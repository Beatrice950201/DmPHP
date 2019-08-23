<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/8/9
 * Time: 9:46
 */

namespace app\common\service;

use Phalcon\Di\FactoryDefault;

class Service extends FactoryDefault
{

    protected $data = [];

    protected $status = false;

    protected $message = "";


    /**
     * 初始化
     * 新建构造
     * Base constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if(method_exists($this,'initialize')){
            $this->initialize();
        }
    }


    /**写入成功
     * User:一根小腿毛
     * @param null $data
     * @return Service
     */
    public function success($data):Service
    {
        $this->status = true;
        $this->data = $data;
        return $this;
    }

    /**
     * 获取消息
     * @return string
     */
    public function message():string
    {
        return $this->message;
    }

    /**
     * 写入失败
     * @param string $errorMessage
     * @return $this
     */
    public function fail(string $errorMessage):Service
    {
        $this->status = false;
        $this->message = $errorMessage;
        return $this;
    }

    /**
     * 获取data
     * @return array |null
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * 是否成功
     * @return bool
     */
    public function is_success():bool
    {
        return $this->status;
    }

}