<?php

require_once("assets/vendor/fat-free-framework-3.6.4/base.php");
require_once("app/controller.php");

$f3 = Base::instance();

$f3->config('assets/settings/config.ini');
require_once("assets/settings/auto_routes.php");
$f3->config('assets/settings/routes.ini');

$f3->run();
