<?php

namespace app\admin\model;

use think\Model;


class Account extends Model
{

    

    

    // 表名
    protected $name = 'account';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
//    protected $createTime = 'createtime';
//    protected $updateTime = 'updatetime';
//    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'cloud_type_text'
    ];
    

    
    public function getCloudTypeList()
    {
        return ['huawei' => __('Huawei'), 'aliyun' => __('Aliyun')];
    }


    public function getCloudTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['cloud_type']) ? $data['cloud_type'] : '');
        $list = $this->getCloudTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public static function getList($user=""){

        $model = self::field("cloud_type,key,secrect,user,iam_name");
        if($user){
            $model = $model->where('user','=',$user);
        }
        return $model->order('id desc')->select();
    }




}
