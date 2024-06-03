<?php
include_once('verify.php');
$obj_domain = new CheckDomain();

function sendResponse($msg = "",$verified = false) {
    $responseData = array(
        'message' => $msg,
        'verified' => $verified
    );
    echo json_encode($responseData);
}


if (isset($_GET["domain"]) && isset($_GET["captcha"])) {
    session_start();

    $domain = $_GET["domain"];
    $captcha = $_GET["captcha"];

    if (strtolower($captcha) === $_SESSION["captcha"]) {
        $res = $obj_domain->checkDomainExits($domain);
        if ($res === false) {
            sendResponse("域名未通过一号站验证,谨防假冒平台！", false);
        } else {
            sendResponse("恭喜您的域名通过一号站验证,请放心使用！", true);
        }
    } else {
        sendResponse("验证码不正确", false);
    }
} else {
    sendResponse("请输入验证码以及域名后提交", false);
}
?>