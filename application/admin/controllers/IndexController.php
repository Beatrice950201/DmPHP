<?php
/**
 *QQ:1368213727
 *User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/4/15
 * Time: 10:09
 */

namespace app\admin\controllers;

use app\admin\facade\TestFacade;
use library\facade\Hook;
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{

       public function indexAction(){
            $data = [];
            $data["test"] = TestFacade::test()->data();
            Hook::execute("test_hook");//测试钩子函数
            $this->view->setVars($data);
       }


}
