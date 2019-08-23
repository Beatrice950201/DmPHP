<?php
namespace app\command\tasks;

use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function mainAction()
    {
       var_dump("is consoleMainTask!!!");
    }

}
