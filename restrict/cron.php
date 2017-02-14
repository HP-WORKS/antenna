<?php

//制限時間を消す
set_time_limit(0);

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'cli'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APPLICATION_PATH . '/../library')
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
	APPLICATION_ENV,
	APPLICATION_PATH . '/configs/application.ini'
);
require_once APPLICATION_PATH . "/Router.php";

$application->bootstrap();
$application
	->getBootstrap()
	->getResource('FrontController')
	->setResponse(new Zend_Controller_Response_Cli())
	->setRouter(new Application_Router())
	->setRequest(new Zend_Controller_Request_Simple("index", "cron", "cli"))
;
$application->run();

