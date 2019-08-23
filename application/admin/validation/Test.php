<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/8/20
 * Time: 9:36
 */

namespace app\admin\validation;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;

class Test extends Validation
{
    /**
     * 测试验证器案例
     * User:一根小腿毛；
     * QQ:1368213727
     */
    public function initialize(){
        $this->add(
            ["title","content","users"],
            new PresenceOf([
                "message" => [
                    "title"    => "请填写标题~",
                    "content"  => "请填写内容~",
                    "users"    => "请选择用户~",
                ],
            ])
        );
    }

}
