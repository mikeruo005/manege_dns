<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\library\Alicloud;
use app\common\library\Huaweicloud;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\response\Json;

/**
 * 域名管理
 *
 * @icon fa fa-circle-o
 */
class Domains extends Backend
{

    /**
     * Domains模型对象
     * @var \app\admin\model\Domains
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Domains;
        $this->view->assign("domainloggingswitchstatusList", $this->model->getDomainloggingswitchstatusList());
        $this->view->assign("accountUserList", $this->model->getAccountUsers());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     *
     * @return string|Json
     * @throws \think\Exception
     * @throws DbException
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if (false === $this->request->isAjax()) {
            return $this->view->fetch();
        }
        //如果发送的来源是 Selectpage，则转发到 Selectpage
        if ($this->request->request('keyField')) {
            return $this->selectpage();
        }
        [$where, $sort, $order, $offset, $limit] = $this->buildparams();
        if($this->request->get('cloud_type')){
            $where['cloud_type'] = ['=',$this->request->get('cloud_type')];
        }

        $list = $this->model
            ->where($where)
            ->order($sort, $order)
            ->paginate($limit);
        $result = ['total' => $list->total(), 'rows' => $list->items()];
        return json($result);
    }


    /**
     * 添加
     *
     * @return string
     * @throws \think\Exception
     */
    public function add()
    {
        if (false === $this->request->isPost()) {
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);

        $one = \app\admin\model\Account::get(['user'=>$params['account_id']]);

        if(!$one){
            $this->error(__('云账号不存在', ''));
        }
        if($one['cloud_type'] == 'huawei'){
            $hadle = new Huaweicloud($one['key'],$one['secrect']);
            try {
                $respone = $hadle->joinDomain($params['DomainName']);
            }catch (\Exception $e){
                $this->error('错误信息:'.$e->getMessage());
            }

            if(!isset($respone['id']) || !isset($respone['name'])){
                $this->error(__('域名添加华为云错误', ''));
            }
            $params['cloud_type'] = $one['cloud_type'];
            $params['DomainId'] = $respone['id'];
            $params['PunyCode'] = $params['DomainName'];
            $params['CreateTime'] = date('Y-m-d H:i:s');
            $params['DnsServers'] = 'ns1.huaweicloud-dns.org,ns1.huaweicloud-dns.com';
            $params['RecordCount'] = $respone['record_num'];

        }else{
//            exit($params['DomainName']);
            $hadle = new Alicloud($one['key'],$one['secrect']);
            try {
                $respone = $hadle->joinDomain($params['DomainName']);
            }catch (\Exception $e){
                $this->error('错误信息:'.$e->getMessage());
            }

            if(!isset($respone['DomainId'])){
                $this->error(__('域名添加阿里云错误', ''));
            }
            $params['cloud_type'] = $one['cloud_type'];
            $params['DomainId'] = $respone['DomainId'];
            $params['PunyCode'] = $params['DomainName'];
            $params['CreateTime'] = date('Y-m-d H:i:s');
            $params['DnsServers'] = 'ns7.alidns.com,ns8.alidns.com';

        }



        if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
            $params[$this->dataLimitField] = $this->auth->id;
        }
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                $this->model->validateFailException()->validate($validate);
            }
            $result = $this->model->allowField(true)->save($params);
            Db::commit();
        } catch (ValidateException|PDOException|\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($result === false) {
            $this->error(__('No rows were inserted'));
        }
        $this->success();
    }


    /**
     * 删除
     *
     * @param $ids
     * @return void
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function del($ids = null)
    {
        if (false === $this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ?: $this->request->post("ids");
        if (empty($ids)) {
            $this->error(__('Parameter %s can not be empty', 'ids'));
        }
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        $list = $this->model->where($pk, 'in', $ids)->select();

        $count = 0;
        Db::startTrans();
        try {
            foreach ($list as $item) {
                if($item['cloud_type'] == 'huawei'){
                    $one = \app\admin\model\Account::get(['user'=>$item['account_id']]);
                    $hadle = new Huaweicloud($one['key'],$one['secrect']);
                    try {
                        $respone = $hadle->delDomain($item['DomainId']);
                    }catch (\Exception $e){
                        $this->error('错误信息:'.$e->getMessage());
                    }


                    if(!isset($respone['id']) || !isset($respone['name'])){
                        $this->error(__('删除域名失败', ''));
                    }

                }else{
                    $one = \app\admin\model\Account::get(['user'=>$item['account_id']]);
                    $hadle = new Alicloud($one['key'],$one['secrect']);
                    try {
                        $respone = $hadle->delDomain($item['DomainName']);
                    }catch (\Exception $e){
                        $this->error('错误信息:'.$e->getMessage());
                    }




                }


                $count += $item->delete();
            }
            Db::commit();
        } catch (PDOException|Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($count) {
            $this->success();
        }
        $this->error(__('No rows were deleted'));
    }






}
