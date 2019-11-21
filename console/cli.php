<?php
// command request
define('APP_PATH', dirname(dirname(__FILE__)));
if (!file_exists(APP_PATH . '/vendor/autoload.php')) {
	exit(APP_PATH . '/vendor/autoload.php is not exits');
}
require_once APP_PATH . '/vendor/autoload.php';

$application = new Yaf\Application(APP_PATH . "/config/app.ini");
$application->bootstrap()->getDispatcher()->dispatch(new Yaf\Request\Simple());
