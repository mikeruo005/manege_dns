<?php

namespace app\common\model;

use think\Model;


class Tguser extends Model
{

    

    

    // 表名
    protected $name = 'tguser';
    
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

    public static function addUser($tg_id,$tg_nick,$tg_group_id,$tg_group_name,$invite_id="",$invite_nick=""){
        $where = [
            'tg_account' => ['=',$tg_id],
            'tg_group_account' =>['=',$tg_group_id]
        ];
        $model = self::where($where)->count();
        if(!$model){
            $data = [
                'tg_account'        => $tg_id,
                'tg_nick'           => $tg_nick,
                'tg_group_account'  => $tg_group_id,
                'tg_group_name'     => $tg_group_name,
                'invite_tg_account' => $invite_id,
                'invite_tg_nick'    => $invite_nick,
            ];
            return self::insert($data);
        }
    }






}
