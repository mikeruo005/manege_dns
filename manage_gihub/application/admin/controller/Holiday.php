<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\library\Tg;
use think\Db;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Holiday extends Backend
{

    /**
     * Holiday模型对象
     * @var \app\admin\model\Holiday
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Holiday;
        $this->view->assign("checkStatusList", $this->model->getCheckStatusList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 编辑
     *
     * @param $ids
     * @return string
     * @throws DbException
     * @throws \think\Exception
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds) && !in_array($row[$this->dataLimitField], $adminIds)) {
            $this->error(__('You have no permission'));
        }
        if (false === $this->request->isPost()) {
            $this->view->assign('row', $row);
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        $nowtime = date('Y-m-d H:i:s');
        if($row->check_status == '0'){
            if($params['check_status'] == '1'){
                $params['check_name'] = $this->auth->username;
                $msg = "用户:$row->tg_nick 
用户标识: $row->tg_account 
打卡类型: 请假 
时间: $nowtime 
状态: 成功 
提示:你的请假申请已通过";
                Tg::sendMessage($msg,$row->chat_id,$row->message_id,$row->username);
            }elseif($params['check_status'] == '2'){
                $params['check_name'] = $this->auth->username;
                $msg = "用户:$row->tg_nick 
用户标识: $row->tg_account 
打卡类型: 请假 
时间: $nowtime 
状态: 失败 
提示:你的请假申请已拒绝";
                Tg::sendMessage($msg,$row->chat_id,$row->message_id,$row->username);
            }
        }
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                $row->validateFailException()->validate($validate);
            }
            $result = $row->allowField(true)->save($params);
            Db::commit();
        } catch (ValidateException|PDOException|\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if (false === $result) {
            $this->error(__('No rows were updated'));
        }
        $this->success();
    }




}
