<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/8/6
 * Time: 17:32
 */

namespace library;

use Phalcon\Mvc\Controller AS Phalcon;

abstract class Controller extends Phalcon
{
    public function initialize(){}

    /**
     * 返回错误信息
     * @param string|null $message
     * @param string $url
     * @param int $code
     * @return string
     * @author 一根小腿毛 <1368213727@qq.com>
     */
    protected function error(string $message = null, string $url = null, int $code = 0)
    {
        if ($this->request->isAjax()) {
            $this->sendJson([
                'message'=>$message,
                'url'=>$url,
                'code'=>$code,
                'status'=>false
            ]);
        }else{
            var_dump("没有开发好！！！");
        }
    }

    /**
     * 成功返回数据
     * @param string $data
     * @param string|null $message
     * @param string $url
     * @param int $code
     * @return string
     * @author 一根小腿毛 <1368213727@qq.com>
     */
    protected function success($data = null, string $message = null, string $url = null, int $code = 0){
        if ($this->request->isAjax()) {
            $this->sendJson([
                'data'=>$data,
                'message'=>$message,
                'url'=>$url,
                'code'=>$code,
                'status'=>true
            ]);
        }else{
            var_dump("没有开发好！！！");
        }
    }

    /**
     * 发送json
     * @author 一根小腿毛 <1368213727@qq.com>
     * @return string
     * @param array $content
     * @param string $code
     */
    protected function sendJson(array $content,$code=''){
        $this->response->setJsonContent($content);
        if($code && is_int($code)){
            $this->response->setStatusCode($code);
        }
        $this->response->send();exit();
    }


}