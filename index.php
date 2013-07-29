<?php
error_reporting(E_ALL^E_NOTICE); 
define('ROOTDIR', dirname(__FILE__));
include ROOTDIR."/common/init.php";

$app = get('app');
$action = get('action');

$action_name = null;



if(!empty($action)) {

	if(!empty($app)) 
		$action_name = ROOTDIR."/action/".$app."/".$action.".php";
	else 
		$action_name = ROOTDIR."/action/".$action.".php";


	if(file_exists($action_name))  {
		include $action_name;	
		exit;
	}

} 
echo "404";
exit;
