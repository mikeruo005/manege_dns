<?php
include_once('client_type.php');
include_once('client_ip.php');
include_once('domain_type.php');

function getDomainList() {
    $ip = getip();
    $domainList = array();
    $domaintype = domaintype();
    $type = isMobile() ? "m" : "p";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "http://dmapi.yhzcs.org/domain_push?ip=" . $ip . "&type=" . $type);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    $domainList['yhz'] = json_decode(curl_exec($curl));
    curl_setopt($curl, CURLOPT_URL, "http://dmapi.2hzcs123.com/domain_push_2hz?ip=" . $ip . "&type=" . $type);
    $domainList['2hz'] = json_decode(curl_exec($curl));
    //curl_setopt($curl, CURLOPT_URL, "http://dmapi.bmcs001.com/domain_push_lgv?ip=" . $ip . "&type=" . $type);
    //$domainList['bm'] = json_decode(curl_exec($curl));
    curl_close($curl);
    //$domainList['type'] = $domaintype;
    return $domainList;
}

?>