<?php

namespace app\common\library;



use fast\Http;
use GuzzleHttp\Client;
use think\Exception;

class Alicloud
{


    private $key ;

    private $secrect;

    private $regionid;


    private $url = 'http://dns.aliyuncs.com/';



    public function __construct($key="",$secrect="",$regionid='cn-hangzhou')
    {
        $this->key      = $key;
        $this->secrect  = $secrect;
        $this->regionid = $regionid;
        $this->client = new Client();
    }


    /**
     * 获取域名列表
     * @return mixed
     */
    public  function getDomainList(){
        $params = [
            'Action' => 'DescribeDomains',
            'Format'    => 'json',
            'Version' => '2015-01-09',
            'AccessKeyId' => $this->key,
            'SignatureMethod' => 'HMAC-SHA1',
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'SignatureVersion' => '1.0',
            'SignatureNonce' => uniqid(),
            'PageNumber' => 1,
            'PageSize' => 100
        ];

        $params['Signature'] = $this->sign($params);

        $params['PageNumber'] = 1;
        $params['PageSize'] = 100;

        try {
            $res = Http::get($this->url.'?'.http_build_query($params));
        }catch (\Exception $e){
            return $e;
        }


        return json_decode($res,true);
    }


    /**
     * 获取域名列表
     * @return mixed
     */
    public  function getRecordList($DomainName){
        $params = [
            'Action' => 'DescribeDomainRecords',
            'DomainName' => $DomainName,
            'Format'    => 'json',
            'Version' => '2015-01-09',
            'AccessKeyId' => $this->key,
            'SignatureMethod' => 'HMAC-SHA1',
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'SignatureVersion' => '1.0',
            'SignatureNonce' => uniqid(),
            'PageNumber' => 1,
            'PageSize' => 500
        ];

        $params['Signature'] = $this->sign($params);

        $params['PageNumber'] = 1;
        $params['PageSize'] = 500;

        try {
            $res = Http::get($this->url.'?'.http_build_query($params));
        }catch (\Exception $e){
            return $e;
        }


        return json_decode($res,true);
    }




    /**
     * 添加域名
     * @param $domain
     * @return mixed
     * 失败 Array
        (
        [RequestId] => 8F940382-9D5A-5A81-A76D-BFB6B143E4A1
        [HostId] => dns.aliyuncs.com
        [Code] => InvalidDomainName.Unregistered
        [Message] => Unregistered domain name.
        [Recommend] => https://api.alibabacloud.com/troubleshoot?intl_lang=EN_US&q=InvalidDomainName.Unregistered&product=Alidns&requestId=8F940382-9D5A-5A81-A76D-BFB6B143E4A1
        )
     * 成功 Array
        (
        [DomainId] => d787c27930f7445bbbb6aee54a469d99
        [RequestId] => 35FC55A2-6D85-5285-B5D8-097CE67CB8E1
        [DomainName] => dianke360.com
        [PunyCode] => dianke360.com
        [DnsServers] => Array
        (
        [DnsServer] => Array
        (
        [0] => ns7.alidns.com
        [1] => ns8.alidns.com
        )

        )

        )

     */
    public function joinDomain($domain){

        $params = [
            'Action' => 'AddDomain',
            'DomainName' => $domain,
            'Format'    => 'json',
            'Version' => '2015-01-09',
            'AccessKeyId' => $this->key,
            'SignatureMethod' => 'HMAC-SHA1',
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'SignatureVersion' => '1.0',
            'SignatureNonce' => uniqid(),
            'PageNumber' => 1,
            'PageSize' => 30

        ];
        $params['Signature'] = $this->sign($params);
        try {
            $res = Http::get($this->url.'?'.http_build_query($params));
            return json_decode($res,true);
        }catch (\Exception $e){
            return [$e->getMessage()];
        }

    }


    /**
     * 删除域名
     * @param $domain
     * @return mixed
     */
    public function delDomain($domain){
        $params = [
            'Action' => 'DeleteDomain',
            'DomainName' => $domain,
            'Format'    => 'json',
            'Version' => '2015-01-09',
            'AccessKeyId' => $this->key,
            'SignatureMethod' => 'HMAC-SHA1',
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'SignatureVersion' => '1.0',
            'SignatureNonce' => uniqid(),
        ];
        $params['Signature'] = $this->sign($params);
        $res = Http::get($this->url.'?'.http_build_query($params));
        return json_decode($res,true);
    }


    /**
     * 添加/修改记录
     * @param $domain
     * @param $rr
     * @param $type
     * @param $value
     * @return mixed
     */
    public function updateRecord($domain,$rr,$type,$value,$recordid=""){
        $action = "AddDomainRecord";
        if($recordid){
            $action = 'UpdateDomainRecord';
        }
        $params = [
            'Action'            => $action,
            'DomainName'        => $domain,
            'RR'                => $rr,
            'Type'              => $type,
            'Value'             => $value,
            'Format'    => 'json',
            'Version' => '2015-01-09',
            'AccessKeyId' => $this->key,
            'SignatureMethod' => 'HMAC-SHA1',
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'SignatureVersion' => '1.0',
            'SignatureNonce' => uniqid(),
        ];
        if($recordid){
            $params['RecordId'] = $recordid;
        }
        $params['Signature'] = $this->sign($params);
        $res = Http::get($this->url.'?'.http_build_query($params));
        return json_decode($res,true);
    }


    public function delRecord($recordid){
        $params = [
            'Action' => 'DeleteDomainRecord',
            'RecordId' => $recordid,
            'Format'    => 'json',
            'Version' => '2015-01-09',
            'AccessKeyId' => $this->key,
            'SignatureMethod' => 'HMAC-SHA1',
            'Timestamp' => gmdate('Y-m-d\TH:i:s\Z'),
            'SignatureVersion' => '1.0',
            'SignatureNonce' => uniqid(),
        ];
        $params['Signature'] = $this->sign($params);
        $res = Http::get($this->url.'?'.http_build_query($params));
        return json_decode($res,true);
    }



    public function sign($params){
//        $params = array_merge($params,$baseParams);
        ksort($params);


        // 生成签名字符串
        $queryString = http_build_query($params);
        $stringToSign = 'GET&%2F&' . urlencode($queryString);

        // 计算签名
        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $this->secrect . '&', true));
        return $signature;


//        $apiVersion = '2015-01-09'; // 替换为你要使用的 API 版本号
//        // 构造签名
//        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
//        $signatureNonce = uniqid();
//        $stringToSign = 'GET&%2F&' . urlencode('AccessKeyId=' . $this->key . '&Action=' . $action . '&SignatureMethod=HMAC-SHA1&SignatureNonce=' . $signatureNonce . '&SignatureVersion=1.0&Timestamp=' . $timestamp . '&Version=' . $apiVersion);
//        $signature = base64_encode(hash_hmac('sha1', $stringToSign, $this->secrect . '&', true));
//        $url .= '&AccessKeyId=' . $this->key . '&SignatureMethod=HMAC-SHA1&SignatureNonce=' . $signatureNonce . '&SignatureVersion=1.0&Timestamp=' . urlencode($timestamp) . '&Signature=' . urlencode($signature);

    }


}