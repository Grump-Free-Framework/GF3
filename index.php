<?php

require_once("app/application/vendor/fat-free-framework-3.6.4/base.php");

//define base
$f3 = Base::instance();

//configs
$f3->config('config.ini');
$f3->config('app/application/routes.ini');

//map to default route
$f3->map('/', "modules\\{$f3->get('defaultModule')}\\controllers\\Controller");

//php imports
require_once("app/application/php_functions.php");

//setup grumpypdo as db
$f3->set('db', new GrumpyPDO($f3->get('db_host'), $f3->get('db_username'), $f3->get('db_password'), $f3->get('db_database')));

//calculate page load time
$f3->set('loadtime', round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3));
$f3->run();
