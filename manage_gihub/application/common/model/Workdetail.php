<?php

namespace app\common\model;

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


    public static function checkAllow($tgid){
        $nowdate = date('Y-m-d');
        $nowtime = date('Y-m-d H:i:s');
        $where = [
            'tg_account'    => $tgid,
            'set_start_time'=> ['>',$nowtime],
            'set_off_time'  => ['<',$nowtime],
            'status'        => ['=','1'],
        ];
        $res = self::where($where)->find();

        if($res){
            if(!$res['start_work_time']){ //没打上班卡
                return false;
            }elseif(!$res['off_work_time']){ //没打下班卡
                return 1;
            }else{  //  已经下班了
                return 2;
            }
        }else{
            return false;
        }

    }


    public static function createData($tgId,$tg_nick,$department_id){
        $worklist = Department::get($department_id);
        $work_status = '1';
        $w = date('w');
        if ($w == $worklist['reset_day']) {
            $work_status = '0';
        }
        $set_start_time = date('Y-m-d ') . $worklist['start_work_time'];
        $set_off_time  = date('Y-m-d ' . $worklist['off_work_time']);
        if ($worklist['start_work_time'] >= $worklist['off_work_time']) {
            $tomorrow = strtotime('tomorrow');
            $set_off_time = date('Y-m-d ' . $worklist['off_work_time'], $tomorrow);
        }


        $datas = [
            'tg_account'        => $tgId,
            'tg_nick'           => $tg_nick,
            'dates'             => date('Y-m-d'),
            'set_start_time'    => $set_start_time,
            'set_off_time'      => $set_off_time,
            'work_status'       => $work_status,
        ];
        $model = new self();
        $model->create($datas);
        return $model;
    }




}
