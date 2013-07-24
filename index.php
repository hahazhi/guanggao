<?php
define('ROOTDIR', dirname(__FILE__));
include ROOTDIR."/common/init.php";


$action = get('action');

$action_name = null;



if(!empty($action)) {
	$action_name = ROOTDIR."/action/".$action.".php";
	if(file_exists($action_name))  {
		include $action_name;	
		exit;
	}

} 
echo "404";
exit;
