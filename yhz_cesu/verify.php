<?php
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


?>