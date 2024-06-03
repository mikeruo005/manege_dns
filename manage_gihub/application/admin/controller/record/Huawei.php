<?php

namespace app\admin\controller\record;

use app\admin\controller\Account;
use app\admin\model\Domains;
use app\common\controller\Backend;
use app\common\library\Huaweicloud;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\exception\PDOException;
use think\exception\ValidateException;
use think\response\Json;
use think\Validate;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Huawei extends Backend
{

    /**
     * Huawei模型对象
     * @var \app\admin\model\record\Huawei
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\record\Huawei;
        $this->view->assign("typeList", $this->model->getTypeList());
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
        $domain_id="";
        if($domain = $this->request->get('domain','')){
            $domain_id = Domains::where('DomainName','=',$domain)->value('id');
        }
        $this->view->assign('domain_id',$domain_id);
        if (false === $this->request->isPost()) {
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if($params['type']=='CNAME'){
            if(!$params['value']){
                $this->error('请填写CNAME的值');
            }
            $pattern = '/^((?!-)[A-Za-z0-9-]{1,63}(?<!-)\.)+[A-Za-z]{2,6}$/';
            $pvalue = explode(',',$params['value']);
            foreach ($pvalue as $pv){
                if(!preg_match($pattern, $pv)){
                    $this->error("CNAME{{$pv}}格式不正确");
                }
            }

        } elseif ($params['type']=='A'){
            if(!$params['value']){
                $this->error('请填写IP地址');
            }
            $pvalue = explode(',',$params['value']);
            foreach ($pvalue as $pv){
                if(!filter_var($pv, FILTER_VALIDATE_IP)){
                    $this->error("IP{{$pv}}格式不正确");
                }
            }

        }
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        $one = Domains::alias('a')
            ->join('oa_account b','a.account_id=b.user')
            ->field("a.DomainName,a.DomainId,b.key,b.secrect")
            ->where('a.id','=',$params['id'])
            ->find();
        $handle = new Huaweicloud($one['key'],$one['secrect']);
        $rr = $params['rr'].'.'.$one['DomainName'];
        try {
            $respone = $handle->updateRecord($one['DomainId'],$rr,$params['type'],[$params['value']]);
        }catch (\Exception $e){
            $this->error('错误信息:'.$e->getMessage());
        }


        $params['zone_id'] = $respone['id'];
        $params['domain'] = $one['DomainName'];
        unset($params['id']);
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
        } catch (ValidateException|PDOException|Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($result === false) {
            $this->error(__('No rows were inserted'));
        }
        $this->success();
    }


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
        $pvalue = explode(',',$params['value']);
        if($params['type']=='CNAME'){
            if(!$params['value']){
                $this->error('请填写CNAME的值');
            }
            $pattern = '/^((?!-)[A-Za-z0-9-]{1,63}(?<!-)\.)+[A-Za-z]{2,6}$/';
            foreach ($pvalue as $pv){
                if(!preg_match($pattern, $pv)){
                    $this->error("CNAME{{$pv}}格式不正确");
                }
            }

        } elseif ($params['type']=='A'){
            if(!$params['value']){
                $this->error('请填写IP地址');
            }
            foreach ($pvalue as $pv){
                if(!filter_var($pv, FILTER_VALIDATE_IP)){
                    $this->error("IP{{$pv}}格式不正确");
                }
            }

        }
        $params = $this->preExcludeFields($params);
        //加入云
        $one = Domains::alias('a')
            ->join('oa_account b','a.account_id=b.user')
            ->field("a.DomainName,a.DomainId,b.key,b.secrect")
            ->where('a.DomainName','=',$row->domain)
            ->find();

        $handle = new Huaweicloud($one['key'],$one['secrect']);
        $rr = $params['rr'].'.'.$one['DomainName'];
        try {
            $respone = $handle->updateRecord($one['DomainId'],$rr,$params['type'],$pvalue,$params['descript'],$row['zone_id']);

        }catch (\Exception $e){
            $this->error('错误信息:'.$e->getMessage());
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
        } catch (ValidateException|PDOException|Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if (false === $result) {
            $this->error(__('No rows were updated'));
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
                //删除
                $one = Domains::alias('a')
                    ->join('oa_account b','a.account_id=b.user')
                    ->field("a.DomainName,a.DomainId,b.key,b.secrect")
                    ->where('a.id','=',$item['id'])
                    ->find();
                $handle = new Huaweicloud($one['key'],$one['secrect']);
                try {
                    $handle->delRecord($one['DomainId'],$item['zone_id']);
                }catch (\Exception $e){
                    $this->error('错误信息:'.$e->getMessage());
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
