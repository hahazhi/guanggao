<?php
$db_config = array(
		'host'=>'127.0.0.1',
		'user'=>'root',
		'pass'=>'',
		'dbname'=>'guanggao',
		'charset'=>'utf8'
);


$rewrite_config = array(
		'/'=>array('action'=>'index'),
		'/test/?'=>array('action'=>'test'),
		'/detail/(?P<id>\d+)/?'=>array('action'=>'detail'),
		
);


//不需要登陆action
$nologin_action = array('login', 'reg', 'code');
