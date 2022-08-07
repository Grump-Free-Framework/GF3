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
	$f3->set('db', new GrumpyPDO($f3->get('dbHost'), $f3->get('dbUser'), $f3->get('dbPassword'), $f3->get('dbDatabase')));
}

//calculate page load time
$f3->set('loadtime', round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3));
$f3->run();
