<?php
function domaintype() {
	    $url = $_SERVER['SERVER_NAME'];
	    $type = '';
	    if(strpos($url, 'ehz') !== false || strpos($url, 'yhz') !== false){
            $type = 'yhz';
        }
        if(strpos($url,'2hz') !==  false){
            $type = '2hz';
        }
        return $type;
}
?>