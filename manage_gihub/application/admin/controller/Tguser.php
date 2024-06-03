<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\exception\DbException;
use think\response\Json;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Tguser extends Backend
{

    /**
     * Tguser模型对象
     * @var \app\admin\model\Tguser
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Tguser;
        $this->view->assign("statusList", $this->model->getStatusList());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    public function department(){
        $departments = \app\admin\model\Department::where(['status'=>'1'])->field(['id','name'])->select();
        $depa = [];
        foreach ($departments as $dep){
            $depa[$dep['id']] = $dep['name'];
        }
        return json($depa);
    }



}
