<?php

namespace app\admin\facade;

use library\Facade;

class TestFacade extends Facade
{
    protected  static function getFacadeClass()
    {
        return "app\\admin\\service\\Test";
    }
}
