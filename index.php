<?php

require_once("app/application/vendor/f3-3.8/base.php");

//define base
$f3 = Base::instance();

//configs
$f3->config('config.ini.php');
$f3->config('app/application/routes.ini');

$f3->map('/', "modules\\{$f3->get('defaultModule')}\\Controller");

//setup grumpypdo as db
if(!empty($f3->get('db_username'))) {
	$f3->set('db', new GrumpyPDO($f3->get('db_host'), $f3->get('db_username'), $f3->get('db_password'), $f3->get('db_database')));
	foreach($f3->get('sensitive_config_keys') as $sensitive_config_key) {
		$f3->clear($sensitive_config_key);
	}
	$f3->clear('sensitive_config_keys');
}

//calculate page load time
$f3->set('loadtime', round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3));
$f3->run();
