<?php

namespace app\common\model;

use think\db\Expression;
use think\Model;


class Rest extends Model
{

    

    

    // 表名
    protected $name = 'rest';
    
    // 自动写入时间戳字段
    //protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
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


    public static function leave_data($tg_account,$set_start_time,$set_off_time){
        $now = time();
        $set_start_time = strtotime($set_start_time);
        $set_off_time   = strtotime($set_off_time);
        $where = [
            'tg_account'    => ['=',$tg_account],
            'leave_time'    => ['between',[$set_start_time,$set_off_time]],
            'status'        => ['=','1'],
        ];

        $res = self::where($where)->field("count(*) as times,sum(seconds) as seconds")->find();

        if($res){
            if($now > $set_off_time){ //过了下班时间
                //没有下班卡
                $where['back_time'] =['exp',new Expression('IS NULL')];
                $model= self::where($where)->find();
                if($model){
                    $model->back_time = $set_off_time;
                    $model->seconds     = $set_off_time - $model->leave_time;
                    $model->save();
                }

            }

            $data['leave_times'] = intval($res['times']);
            $data['leave_table_mins']    = intval($res['seconds']);
            return $data;
        }
        return false;
    }




}
