<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/8/9
 * Time: 13:57
 */

namespace app\admin\service;

use app\common\service\Service;

class Test extends Service
{


    /**
     * 构造函数
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public function initialize(){}

    /**
     * 实例
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public function test(){
        return $this->success(["message"=>"这是使用服务层和门面使用案例！！！"]);
        //return $this->fail("操作失败！");
    }

}
