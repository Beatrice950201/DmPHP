<?php
/**
 * QQ:1368213727
 * User:一根小腿毛
 * Created by PhpStorm.
 * User: RuoShui
 * Date: 2019/7/30
 * Time: 10:12
 */

namespace library\query;

class Database
{
    private $options = [];

    private $join_manager;

    public static $core_model;

    private static $AND_OPT = ["BETWEEN", "&"];

    /**
     * 构造函数
     * User:一根小腿毛；
     * QQ:1368213727
     * @param $class
     */
    public function __construct($class){
        self::$core_model = $class;
    }

    /**
     * 查询
     */
    public function _find(){
        if($this->join_manager){
            $res = $this->build_finds();
            return  $res ? $res[0] : [] ;
        }else{
            return self::$core_model::findFirst($this->options);
        }
    }
    public function _select(){
        if($this->join_manager){
            return $this->build_finds();
        }else{
            return self::$core_model::find($this->options);
        }
    }

    /**
     * 添加
     * @param array $data
     * @return bool
     */
    public function _insert(array $data){
        foreach ($data as $key=>$value){
            self::$core_model->$key = $value;
        }
        $res = self::$core_model->create();
        if($res === false){
            foreach (self::$core_model->getMessages() as $message) {
                $model = get_class(self::$core_model);
                $new = new $model();
                $new::$error = $message->getMessage();
                break;
            }
        }
        return $res;
    }

    /**
     * 更新
     * @param array $data
     * @return bool
     */
    public function _update(array $data =[])
    {
        $model_obj = $this->_find();
        foreach ($data as $key=>$val){
            $model_obj->$key = $val;
        }
        $res = $model_obj->update();
        if(false === $res){
            foreach ($model_obj->getMessages() as $message) {
                $model = get_class(self::$core_model);
                $new = new $model();
                $new::$error = $message->getMessage();
                break;
            }
        }
        return $res;
    }

    /**
     * 删除数据
     */
    public function _delete(){
        return $this->_find()->delete();
    }

    /**
     * join
     * User:一根小腿毛；
     * QQ:1368213727
     * @param string $namespace
     * @param string $conditions
     * @param string $alias
     * @return $this
     */
    public function left($namespace='',$conditions='',$alias=''){
        return $this->create_builder(function () use ($namespace,$conditions,$alias){
            $this->join_manager = $this->join_manager->leftJoin($namespace, $conditions, $alias);
            return $this;
        });
    }
    public function right($namespace='',$conditions='',$alias=''){
        return $this->create_builder(function () use ($namespace,$conditions,$alias){
            $this->join_manager = $this->join_manager->rightJoin($namespace, $conditions, $alias);
            return $this;
        });
    }
    public function inner($namespace='',$conditions='',$alias=''){
        return $this->create_builder(function () use ($namespace,$conditions,$alias){
            $this->join_manager = $this->join_manager->innerJoin($namespace, $conditions, $alias);
            return $this;
        });
    }
    public function build($alias = "a"){
        $this->join_manager = self::$core_model->getModelsManager()->createBuilder()->from([$alias =>get_class(self::$core_model)]);
        return $this;
    }
    private function create_builder($func){
        if(!$this->join_manager){
            self::build();
        }
        return $func();
    }
    private function build_finds(){
       $method = ["columns"=>"columns", "conditions"=>"where", "order"=>"orderBy", "limit"=>"limit"];
       foreach ($this->options as $k=>$v){
           if(isset($method[$k])){
               $func = $method[$k];
               switch ($k){
                   case "conditions":
                       $this->join_manager->$func($v,$this->options["bind"]);
                   break;
                   case "limit":
                       $this->join_manager->$func($this->options["limit"],$this->options["offset"]);
                   break;
                   default:
                       $this->join_manager->$func($v);
               }
           }
       }
       return $this->join_manager->getQuery()->execute();
    }

    /**
     * 事物
     * @author 一根小腿毛 <1368213727@qq.com>
     * @return string
     */
    public function _begin(){
        return self::$core_model->getDI()->get('db')->begin();
    }
    public function _rollback(){
        return self::$core_model->getDI()->get('db')->rollback();
    }
    public function _commit(){
        return self::$core_model->getDI()->get('db')->commit();
    }

    /**
     * cache(true)
     * 缓存策略
     * @param bool $cache
     * @return $this
     */
    public function cache($cache =true){
        if($cache && false === config("app.app_debug")){
            $this->options = array_merge($this->options,[
                "cache"=>[
                    "lifetime" => config("app.cache_time"),
                    "key" =>md5(self::create_key($this->options))
                ]
            ]);
        }
        return $this;
    }
    private static function create_key($parameters)
    {
        $uniqueKey = array();
        foreach ($parameters as $key => $value) {
            if (is_scalar($value)) {
                $uniqueKey[] = $key . ':' . $value;
            } else {
                if (is_array($value)) {
                    $uniqueKey[] = $key . ':[' . self::create_key($value) .']';
                }
            }
        }
        return join(',', $uniqueKey);
    }

    /**
     * 条件
     * @param array $where
     * @return Database
     */
    public function where(array $where=[]){
        $int_array=["conditions"=>"","bind"=>[]];
        foreach ($where as $k=>$v){
            $symbolic = "=";
            $alias = ":{$k}:";$bind_name = $k;
            if(count(explode(".",$k)) == 2){
                $alias =  ':'.explode(".",$k)[1].':';
                $bind_name = explode(".",$k)[1];
            }
            if(is_array($v) && count($v) == 2){
                $symbolic = strtoupper($v[0]);
                $v = $v[1];
                if($symbolic == "IN"){
                    $alias = "({".explode(".", $k)[count(explode(".", $k))-1]."_list:array})";
                };
            }
            if(!in_array($symbolic,self::$AND_OPT)){
                $int_array['conditions'] .="{$k} {$symbolic} {$alias} AND ";
            }else{
                $int_array['conditions'] .= "{$k} {$symbolic} {$v} AND ";
            }
            ($symbolic == "IN") &&  $bind_name = "{$bind_name}_list";
            if(!in_array($symbolic,self::$AND_OPT)){
                $int_array["bind"][$bind_name]=$v;
            }
        }
        $int_array['conditions'] = rtrim($int_array['conditions']," AND ");
        $this->options = array_merge($this->options,$int_array);
        return $this;
    }

    /**
     * field("id,title") || field(['id','title'])
     * @param $field
     * @return $this
     * 获取字段
     */
    public function field($field = true){
        $field_str = '';
        if(is_array($field)){
            $field_str = implode(",",$field);
        }elseif(0 < count(explode(",",$field))){
            $field_str = $field;
        }
        if($field !== true && $field !== '*' && $field_str){
            $this->options = array_merge($this->options,["columns"=>$field_str]);
        }
        return $this;
    }

    /**
     * order("id DESC")
     * 排序方式
     * @param null $order
     * @return $this
     */
    public function order($order = null){
        if(is_string($order)){
            $this->options = array_merge($this->options,["order"=>$order]);
        }
        return $this;
    }

    /** limit(["offset"=>0,"limit"=>10])
     * @param array $limit
     * @return Database
     */
    public function limit(array $limit){
        $this->options = array_merge($this->options,$limit);
        return $this;
    }

    /**
     * for_update(true)
     * 通过这个选项， Phalcon\Mvc\Model 读取最新的可用数据，并且为读到的每条记录设置独占锁。
     * @param bool $is
     * @return Database
     */
    public function for_update($is = false){
        $this->options = array_merge($this->options,["for_update"=>$is]);
        return $this;
    }

    /**
     * shared_lock(true)
     * 通过这个选项， Phalcon\Mvc\Model 读取最新的可用数据，并且为读到的每条记录设置共享锁。
     * @param bool $is
     * @return Database
     */
    public function shared_lock($is = false){
        $this->options = array_merge($this->options,["shared_lock"=>$is]);
        return $this;
    }


}