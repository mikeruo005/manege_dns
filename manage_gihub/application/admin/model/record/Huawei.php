<?php

namespace app\admin\model\record;

use think\Model;


class Huawei extends Model
{

    

    

    // 表名
    protected $name = 'record_huawei';
    
    // 自动写入时间戳字段
//    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
//    protected $createTime = 'createtime';
//    protected $updateTime = 'updatetime';
//    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'type_text'
    ];
    

    
    public function getTypeList()
    {
        return ['A' => __('A'), 'NS' => __('NS'), 'MX' => __('MX'), 'TXT' => __('TXT'), 'CNAME' => __('CNAME'), 'SRV' => __('SRV'), 'AAAA' => __('AAAA')];
    }

    public function getStatusList()
    {
        return [
            'ACTIVE' => __('ACTIVE'),
            'ERROR' => __('ERROR'),
            'DISABLE' => __('DISABLE'),
            'FREEZE' => __('FREEZE'),
            'PENDING_CREATE' => __('PENDING_CREATE'),
            'PENDING_UPDATE' => __('PENDING_UPDATE'),
            'PENDING_DELETE' => __('PENDING_DELETE')
        ];
    }

    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function saveOrInsert($data,$conditon){
        $model = self::where($conditon)->find();

        if($model){
            return $model->save($data);
        }else{
            return self::insert($data);
        }
    }


}
