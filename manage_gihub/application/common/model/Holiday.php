<?php

namespace app\common\model;

use think\Model;


class Holiday extends Model
{

    

    

    // 表名
    protected $name = 'holiday';
    
    // 自动写入时间戳字段
    //protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'check_status_text',
        'status_text'
    ];
    

    
    public function getCheckStatusList()
    {
        return ['0' => __('Check_status 0'), '1' => __('Check_status 1'), '2' => __('Check_status 2')];
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1'), '2' => __('Status 2')];
    }


    public function getCheckStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['check_status']) ? $data['check_status'] : '');
        $list = $this->getCheckStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    /**
     * @param $tg_account
     * @param $set_start_time
     * @param $set_off_time
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function is_ask($tg_account,$set_start_time,$set_off_time){
        //是否有请假
        $holw = [
            'tg_account'    => ['=',$tg_account],
            'start_time'    => ['between',[$set_start_time,$set_off_time]],
            'status'        => ['=','1'],
            'check_status'  => ['=','1']
        ];
        $holidaylist = Holiday::where($holw)->order('id desc')->find();
        if($holidaylist){
            if($set_start_time > $holidaylist['start_time'] &&
                $set_off_time < $holidaylist['end_time']){ //超过一整天

                $data['ask_start_time'] = $set_off_time;
                $data['ask_end_time']   = $set_start_time;
                $data['ask_reset_hours'] = strtotime($set_off_time) - strtotime($set_start_time);
            }elseif($set_start_time >= $holidaylist['start_time'] &&
                $set_off_time >= $holidaylist['end_time']){ //从上班前请假到下班前

                $data['ask_start_time'] = $set_off_time;
                $data['ask_end_time']   = $holidaylist['end_time'];
                $data['ask_reset_hours'] = strtotime($holidaylist['end_time']) - strtotime($set_start_time);
            }elseif($set_start_time <= $holidaylist['start_time'] &&
                $set_off_time <= $holidaylist['end_time']){ //上班中途请假

                $data['ask_start_time'] = $holidaylist['start_time'];
                $data['ask_end_time']   = $holidaylist['end_time'];
                $data['ask_reset_hours'] = strtotime($holidaylist['end_time']) - strtotime($holidaylist['start_time']);
            }elseif($set_start_time <= $holidaylist['start_time'] &&
                $set_off_time >= $holidaylist['end_time']){ //上班后开始请假到下班后

                $data['ask_start_time'] = $holidaylist['start_time'];
                $data['ask_end_time']   = $set_off_time;
                $data['ask_reset_hours'] = strtotime($set_off_time) - strtotime($holidaylist['start_time']);
            }
            $data['work_status'] = '2';
            return $data;
        }else{
            return false;
        }
    }




}
