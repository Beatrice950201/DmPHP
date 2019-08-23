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
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{

       public function indexAction(){
            $data = [];
            $data["test"] = TestFacade::test()->data();
            $this->view->setVars($data);
       }


}
