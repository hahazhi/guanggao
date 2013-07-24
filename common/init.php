<?php
include ROOTDIR."/config/config.php";
include ROOTDIR."/class/mysql.class.php";
include ROOTDIR."/class/generator.class.php";
include ROOTDIR."/common/function.php";


$db =  mysql::getHandle($db_config);

if(!$db) {
	echo "db not connection!";
	exit;
}
$params = parseUrl($rewrite_config);

if(!empty($params))
	foreach($params as $k => $param)
{
	$_GET[$k] = $param;
}
