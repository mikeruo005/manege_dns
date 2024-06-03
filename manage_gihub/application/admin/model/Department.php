<?php

namespace app\admin\model;

use think\Model;


class Department extends Model
{

    

    

    // 表名
    protected $name = 'department';
    
    // 自动写入时间戳字段
    //protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'reset_day_text',
        'status_text'
    ];
    

    
    public function getResetDayList()
    {
        return ['0' => __('Reset_day 0'), '1' => __('Reset_day 1'), '2' => __('Reset_day 2'), '3' => __('Reset_day 3'), '4' => __('Reset_day 4'), '5' => __('Reset_day 5'), '6' => __('Reset_day 6')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1'), '2' => __('Status 2')];
    }


    public function getResetDayTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['reset_day']) ? $data['reset_day'] : '');
        $list = $this->getResetDayList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
