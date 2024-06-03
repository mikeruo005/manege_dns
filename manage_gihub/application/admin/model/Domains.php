<?php

namespace app\admin\model;

use think\Model;


class Domains extends Model
{

    

    const RECORD_TYPE = ['A','NS','MX','TXT','CNAME','SRV','AAAA'];
    

    // 表名
    protected $name = 'domains';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'DomainLoggingSwitchStatus_text'
    ];
    

    
    public function getDomainloggingswitchstatusList()
    {
        return ['OPEN' => __('Open'), 'CLOSE' => __('Close')];
    }


    public function getDomainloggingswitchstatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['DomainLoggingSwitchStatus']) ? $data['DomainLoggingSwitchStatus'] : '');
        $list = $this->getDomainloggingswitchstatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }



    public function getAccountUsers(){
        $list = Account::all();

        $result = [];
        foreach ($list as $k => $v){
            $result[$v['user']] = $v['user'];
        }
        return $result;
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
