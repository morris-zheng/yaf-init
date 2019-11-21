<?php
// define
define('APP_PATH',  realpath(dirname(__FILE__) . '/../'));
// composer vendor
require APP_PATH . '/vendor/autoload.php';
// init framework and run
$app  = new Yaf\Application(APP_PATH . "/config/app.ini");
$app->bootstrap()->run();
