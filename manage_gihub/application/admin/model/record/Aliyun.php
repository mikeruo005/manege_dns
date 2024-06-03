<?php

namespace app\admin\model\record;

use think\Model;


class Aliyun extends Model
{

    

    

    // 表名
    protected $name = 'record_aliyun';
    
    // 自动写入时间戳字段
//    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
//    protected $createTime = 'createtime';
//    protected $updateTime = 'updatetime';
//    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'Type_text'
    ];
    

    
    public function getTypeList()
    {
        return ['A' => __('A'), 'NS' => __('NS'), 'MX' => __('MX'), 'TXT' => __('TXT'), 'CNAME' => __('CNAME'), 'SRV' => __('SRV'), 'AAAA' => __('AAAA')];
    }


    public function getTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['Type']) ? $data['Type'] : '');
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
