<?php
include_once('client_type.php');


$type = isMobile();
if ($type) {
    include_once('mm.cesu.html');
    exit;
}
include_once('cesu.html');

?>