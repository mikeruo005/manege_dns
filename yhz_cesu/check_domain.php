<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/5/3
 * Time: 21:45
 */


error_reporting("E_ALL");
ini_set("display_errors", 1);

class CheckDomain{

    //api 路径
    private $check_domain_api = 'http://admin.1hz101.com/_api/getdomainlistxml.php';

    public  function getVeryCode(){
        return rand(1,9).rand(1,9).rand(1,9).rand(1,9);
    }

    /**
     * @param $xml
     * @return mixed
     */
    public function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

        foreach($values['RuleHtml'] as $k=>$v){
            $ret[] = $v['Htm'];
        }
        return $ret;
    }

    /**检测域名
     * @param $url
     * @return bool|mixed
     */
    public function checkDomainExits($url){
        if(empty($url)){
            return false;
        }
        $ret = file_get_contents($this->check_domain_api);
        $domain = $this->xmlToArray($ret);
        if(in_array($url,$domain)){
            return true;
        }
        return false;
    }

    /**获取验证码
     * @param $code
     * @return bool
     */
    public function checkVeryCode($code){
        if($code != $_SESSION['code']){
            return false;
        }
        return true;
    }

    /** ajax返回
     * @param $status
     * @param $content
     * @param string $info
     */
    public function ajaxReturn($status,$content,$info=''){
        header('Content-Type:application/json; charset=utf-8');
        $data = array(
            'status'=>$status,
            'data'=>(object)$content,
            'info'=>$info,
        );
        exit(json_encode($data));
    }

}
$ret = session_start();
if($ret === false){
    //重试一次
    $ret = session_start();
    if($ret === false) {
        exit('会话开启失败');
    }
}
$obj_domain = new CheckDomain();
//获取验证码
if($_GET['action'] == 'get_code'){
    $code['code']     = strval($obj_domain->getVeryCode());
    $_SESSION['code'] = $code['code'];
    $obj_domain->ajaxReturn(1,$code);
}
elseif($_GET['action'] == 'check_domain'){



    $url  = strval($_GET['url']);
    $code = intval($_GET['code']);
    if(empty($code) || empty($url)){
        $obj_domain->ajaxReturn(0,'','参数错误');
    }
    //正则匹配域名
    $matches = array();
  /*  $ret = preg_match_all("/^(http|https):\/\/(\w+)\.(\w+)\.(\w+)(\/|\?|\%|\&|=)*$/",$url,$matches);
    if($ret == 0){
        $obj_domain->ajaxReturn(0,'','域名不合法请重新输入');
    }*/
    //验证码校验
    $ret = $obj_domain->checkVeryCode($code);
    if($ret === false){
        $obj_domain->ajaxReturn(0,'','验证码错误');
    }
    //掉接口查看域名是否存在
    $url = str_replace('http://','',$url);
    $url = str_replace('https://','',$url);
    $url = str_replace('/','',$url);
    $url = str_replace('?','',$url);
    $url = str_replace('%','',$url);
    $url = str_replace('&','',$url);
    $ret = $obj_domain->checkDomainExits($url);
    if($ret === false){
        $obj_domain->ajaxReturn(0,'','域名未通过一号站验证,谨防假冒平台！');
    }
    $obj_domain->ajaxReturn(1,'','恭喜您的域名通过一号站验证,请放心使用！');
}
