<?php

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'defined.php'); //开发使用
define('APP_NAME', 'api');
define('APP_PATH', ROOT_PATH.'/app/'.APP_NAME);
require_once(APP_PATH . '/conf/comm.conf.php'); //APP配置
InitPHP::init();