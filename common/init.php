<?php
define('TPL_FRONT', ROOTDIR.'/tpl/front');
define('TPL_ADMIN',  ROOTDIR.'/tpl/admin');

include ROOTDIR."/common/function.php";
include ROOTDIR."/config/config.php";
include ROOTDIR."/class/mysql.class.php";


$db =  mysql::getHandle($db_config);

if(!$db) {
	exit("db not connection!");
}
$params = parseUrl($rewrite_config);

if(!empty($params))
	foreach($params as $k => $param)
{
	$_GET[$k] = $param;
}
