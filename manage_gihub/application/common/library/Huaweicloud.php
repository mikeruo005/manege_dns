<?php

namespace app\common\library;



use app\common\library\huaweiSdk\Request;
use GuzzleHttp\Client;
use think\Exception;
use think\exception\HttpException;

class Huaweicloud
{


    private $self_domain;


    private $projectId;


    private $authUrl = 'https://iam.myhuaweicloud.com/v3/auth/tokens';

    private $url = 'https://dns.myhuaweicloud.com';



    public function __construct($key,$secrect,$projectId='')
    {
//        date_default_timezone_set('UTC');
        $this->key = $key;
        $this->secrect = $secrect;
        $this->projectId = $projectId;
        $this->client = new Client();
    }


    /**
     * 获取域名列表
     * @return mixed
     */
    public  function getDomainList(){
        $method = 'GET';
        $uri = "/v2/zones";
        $queryString = '';

        $headers = $this->setSign($method, $uri, $queryString);

        $response = $this->client->request($method, $this->url . $uri, [
            'headers' => $headers,
        ]);
        return json_decode($response->getBody(), true);
    }



    /**
     * 获取域名列表
     * @return mixed
     */
    public  function getRecordList($zone_id){
        $method = 'GET';
        $uri = "/v2/zones/$zone_id/recordsets";
        $queryString = '';

        $headers = $this->setSign($method, $uri, $queryString);

        $response = $this->client->request($method, $this->url . $uri, [
            'headers' => $headers,
        ]);
        return json_decode($response->getBody(), true);
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
    public function joinDomain($domain,$descript=""){

        $method = 'POST';
        $uri = "/v2/zones";
        $queryString = '';
        $body = [
           'name'  => $domain,
        ];
        if($descript){
            $body['description']= $descript;
        }
        $headers = $this->setSign($method, $uri,$body);

        try {
            $response = $this->client->request($method, $this->url . $uri, [
                'headers' => array_merge($headers,
                    ['Content-Type' => 'application/json']
                ),
                'json' => $body,
            ]);
            return json_decode($response->getBody(), true);
        }catch (\Exception $e){
            return [$e->getMessage()];
        }

    }


    /**
     * 删除域名
     * @param $domain
     * @return mixed
     */
    public function delDomain($zone_id){
        $method = 'DELETE';
        $uri = "/v2/zones/$zone_id";

        $headers = $this->setSign($method, $uri);

        $response = $this->client->request($method, $this->url . $uri, [
            'headers' => array_merge($headers,
                ['Content-Type' => 'application/json']
            ),
        ]);
        return json_decode($response->getBody(), true);
    }


    /**
     * 添加/修改记录
     * @param $zone_id
     * @param $rr
     * @param $type
     * @param $value
     * @param $descript
     * @param $recordid
     * @return mixed
     */
    public function updateRecord($zone_id,$rr,$type,array $value,$descript="",$recordid=""){
        $method = 'POST';
        $uri = "/v2/zones/$zone_id/recordsets";
        if($recordid){
            $method = "PUT";
            $uri = "/v2/zones/$zone_id/recordsets/$recordid";
        }

        $body = [
            'name'  => $rr,
            'type'  => $type,
            'records'  => $value,
        ];
        if($descript){
            $body['description']= $descript;
        }
        $headers = $this->setSign($method, $uri,$body);

        $response = $this->client->request($method, $this->url . $uri, [
            'headers' => array_merge($headers,
                ['Content-Type' => 'application/json']
            ),
            'json' => $body,
        ]);
        return json_decode($response->getBody(), true);
    }


    public function delRecord($zone_id,$recordset_id){
        $method = 'DELETE';
        $uri = "/v2/zones/$zone_id/recordsets/$recordset_id";

        $headers = $this->setSign($method, $uri);

        $response = $this->client->request($method, $this->url . $uri, [
            'headers' => array_merge($headers,
                ['Content-Type' => 'application/json']
            ),
        ]);
        return json_decode($response->getBody(), true);
    }



    public function setSign($method,$uri,$body="",$queryString=""){

        $timestamp = gmdate('Ymd\THis\Z');
        $headers = [
            'host' => 'dns.myhuaweicloud.com',
            'x-sdk-date' => $timestamp,

        ];
        $bodys = "";
        if($body){
            $bodys = json_encode($body);
        }
        $canonicalHeaders = [];
        foreach ($headers as $key => $value) {
            $canonicalHeaders[] = $key . ':' . $value;
        }
        $canonicalHeadersString = implode("\n", $canonicalHeaders);
        $signedHeaders = implode(';', array_keys($headers));
        $uri .="/";
        $canonicalRequest = "$method\n$uri\n$queryString\n$canonicalHeadersString\n\n$signedHeaders\n" . hash('sha256', $bodys);

        $stringToSign = "SDK-HMAC-SHA256\n$timestamp\n" . hash('sha256', $canonicalRequest);

        $signature = hash_hmac('sha256', $stringToSign, $this->secrect);
        $authorizationHeader = "SDK-HMAC-SHA256 Access=$this->key, SignedHeaders=$signedHeaders, Signature=$signature";

        $headers['authorization'] = $authorizationHeader;

        return $headers;
    }



}