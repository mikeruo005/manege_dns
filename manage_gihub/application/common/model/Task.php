<?php

namespace app\common\model;

use think\Model;


class Task extends Model
{

    

    

    // 表名
    protected $name = 'task';
    
    // 自动写入时间戳字段
    //protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1'), '2' => __('Status 2')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

     public static function getList(){
        $res = cache('task_list');
        if(!$res){
            $list = Task::where('status','=','1')
                ->field('name,function')
                ->order('order desc')
                ->select();
            $result = [];
            if($list){
                foreach ($list as $k => $v){
                    $result[$v['name']] = $v['function'];
                }
            }
            $res = json_encode($result);
            cache('task_list',$res);

        }
        return json_decode($res,true);
     }




}
