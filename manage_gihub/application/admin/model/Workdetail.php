<?php

namespace app\admin\model;

use think\Model;


class Workdetail extends Model
{

    

    

    // 表名
    protected $name = 'workdetail';
    
    // 自动写入时间戳字段
    //protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'work_status_text',
        'status_text'
    ];
    

    
    public function getWorkStatusList()
    {
        return ['0' => __('Work_status 0'), '1' => __('Work_status 1'), '2' => __('Work_status 2'), '3' => __('Work_status 3'), '4' => __('Work_status 4'), '5' => __('Work_status 5'), '6' => __('Work_status 6')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1'), '2' => __('Status 2')];
    }


    public function getWorkStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['work_status']) ? $data['work_status'] : '');
        $list = $this->getWorkStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
