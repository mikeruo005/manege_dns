<?php

namespace app\common\library;

use app\admin\model\Account;
use app\admin\model\Domains;
use app\admin\model\record\Aliyun;
use app\admin\model\record\Huawei;
use Psr\Log\AbstractLogger;
use think\Hook;

/**
 * 云服务类
 */
class Cloud
{


    public static function syncData($user="")
    {
        $logstr = "-----开始时间:".date('Y-m-d H:i:s').PHP_EOL;
        $accountList = Account::getList($user);

        $domainModel = new Domains();
        $RecordAliModel = new Aliyun();
        $RecordHuaweiModel = new Huawei();

        if(count($accountList)==0){
            $logstr .= "---结束时间:".date('Y-m-d H:i:s').PHP_EOL.PHP_EOL.PHP_EOL;
            exit('暂未添加云账号');
        }
        $online_domain = [];
        $online_record = [];
        //遍历所有账号
        foreach ($accountList as $account){
            if($account['cloud_type'] =='huawei'){// 华为云账号
                $logstr .= "\t+++获取账号{".$account['user']."}(".$account['cloud_type'].")域名记录".PHP_EOL;
                $handle = new Huaweicloud($account['key'],$account['secrect']);
                $domainList = $handle->getDomainList();
                $domains = $domainList['zones'];
                if(count($domains) == 0){
                    continue;
                }

                foreach ($domains as $domain){
                    $logstr .= "\t\t>>>开始获取域名{".$domain['name']."}解析记录".PHP_EOL;

                    $condition=['DomainId'=>$domain['id']];
                    $toDns = 'ns1.huaweicloud-dns.org,ns1.huaweicloud-dns.com';
                    $data = [
                        'account_id' => $account['user'],
                        'cloud_type' => $account['cloud_type'],
                        'DomainName' => rtrim($domain['name'],'.'),
                        'DomainId' => $domain['id'],
                        'CreateTime' => date('Y-m-d H:i:s',strtotime($domain['created_at'])),
                        'DnsServers' => $toDns,
                        'RecordCount' => $domain['record_num'],
                    ];
                    $online_domain[] = $data['DomainName'];
                    $bool = $domainModel->saveOrInsert($data,$condition);
                    if($bool){
                        sleep(1);
                        $recordList = $handle->getRecordList($domain['id']); //获取解析记录
                        if(!isset($recordList['recordsets']) || count($recordList['recordsets'])==0){
                            $logstr .= "\t\t\t".$data['DomainName'].'暂无解析记录'.PHP_EOL;
                            continue;
                        }
                        $records = $recordList['recordsets'];
                        $ti = 0;
                        foreach ($records as $record){

                            if(!in_array($record['type'],Domains::RECORD_TYPE)){
                                continue;
                            }

                            $online_record[]=$record['id'];
                            $condition = ['zone_id'=>$record['id']];


                            $recordss = array_map(function($value) {
                                return rtrim($value, '.');
                            }, $record['records']);

                            $data = [
                                'domain' => rtrim($record['zone_name'],'.'),
                                'zone_id' => $record['id'],
                                'rr' => rtrim(str_replace($record['zone_name'], "", $record['name']),'.'),
                                'type' => $record['type'],
                                'value' => implode(',',$recordss),
                                'status' => $record['status'],
                                'descript' => $record['description']
                            ];
                            $RecordHuaweiModel->saveOrInsert($data,$condition);

                            $ti++;
                            $logstr .= "\t\t\t(".$ti.")记录:".$data['rr']."-iP:".$data['value'].PHP_EOL;
                            unset($data);
                        }
                    }else{
                        continue;
                    }
                    $logstr .= PHP_EOL;
                }

//               echo "\t+++账号{".$account['user']."}(".$account['cloud_type'].")执行完毕".PHP_EOL;
//               echo PHP_EOL;

            }elseif($account['cloud_type'] =='aliyun'){ //阿里云账号

                $logstr .= "\t+++获取账号{".$account['user']."}(".$account['cloud_type'].")域名记录".PHP_EOL;
                $handle = new Alicloud($account['key'],$account['secrect']);
                $domainList = $handle->getDomainList();
                $domains = $domainList['Domains']['Domain'];

                foreach ($domains as $domain){

                    $logstr .= "\t\t>>>开始获取域名{".$domain['DomainName']."}解析记录".PHP_EOL;
                    $online_domain[] = $domain['DomainName'];
                    $condition=['DomainName'=>$domain['DomainName']];
                    $toDns = isset($domain['DnsServers']['DnsServer'])?implode(',',$domain['DnsServers']['DnsServer']):'ns7.alidns.com,ns8.alidns.com';
                    $data = [
                        'account_id' => $account['user'],
                        'cloud_type' => $account['cloud_type'],
                        'DomainName' => $domain['DomainName'],
                        'DomainId' => $domain['DomainId'],
                        'CreateTime' => date('Y-m-d H:i:s',strtotime($domain['CreateTime'])),
                        'DnsServers' => $toDns,
                        'Punycode'  => $domain['PunyCode'],
                        'VersionCode' => $domain['VersionCode'],
                        'VersionName' => $domain['VersionName'],
                        'RecordCount' => $domain['RecordCount'],
                    ];
                    $bool = $domainModel->saveOrInsert($data,$condition);

                    if($bool){
                        sleep(1);
                        $recordList = $handle->getRecordList($domain['DomainName']); //获取解析记录
                        if(!isset($recordList['DomainRecords']['Record']) || count($recordList['DomainRecords']['Record'])==0){
                            $logstr .= "\t\t\t".$data['DomainName'].'暂无解析记录'.PHP_EOL;
                            continue;
                        }
                        $records = $recordList['DomainRecords']['Record'];
                        $ti=0;
                        foreach ($records as $record){
                            if(!in_array($record['Type'],Domains::RECORD_TYPE)){
                                continue;
                            }
                            $online_record[]=$record['RecordId'];
                            $condition = ['RecordId'=>$record['RecordId']];
                            $data = [
                                'DomainName' => $record['DomainName'],
                                'RecordId' => $record['RecordId'],
                                'RR' => $record['RR'],
                                'Type' => $record['Type'],
                                'Value' => $record['Value'],
                            ];
                            $ti++;
                            $bool = $RecordAliModel->saveOrInsert($data,$condition);
                            $logstr .= "\t\t\t(".$ti.")记录:".$record['RR']."-iP:".$record['Value'].PHP_EOL;

                        }
                    }else{
                        continue;
                    }
                    $logstr .= PHP_EOL;
                }

//               echo "\t+++账号{".$account['user']."}(".$account['cloud_type'].")执行完毕".PHP_EOL;
//               echo PHP_EOL;

            }else{
                continue;
            }

//            $domainModel->where(['DomainName'=>['not in',$online_domain]])->delete();
//            $RecordAliModel->where(['RecordId'=>['not in',$online_record]])->delete();
        }


        $logstr .= "-----结束时间:".date('Y-m-d H:i:s').PHP_EOL.PHP_EOL.PHP_EOL;
        file_put_contents('/tmp/curl-'.date('Ymd').'.log',$logstr,FILE_APPEND);
        return true;

    }
}
