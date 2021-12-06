<?php
$is_cmd_line = isset($_SERVER['argc']) || (php_sapi_name() == 'cli' && empty($_SERVER["REMOTE_ADDRESS"])) || isset($_ENV['SSH_CLIENT'])/* || defined('STDIN')*/;

//Only execute this if command line. If someone try to access this file via http request, this file wno't do anything
if ($is_cmd_line) {
	include_once $EVC->getModulePath("workerpool/WorkerPoolHandler", $EVC->getCommonProjectName());
	
	//Force loglevel to what it is in the command line, even if the project as its own log level.
	$options = getopt("", array("loglevel::"));
	if (is_numeric($options["loglevel"]) && $GLOBALS["GlobalLogHandler"]) 
		$GLOBALS["GlobalLogHandler"]->setLogLevel($options["loglevel"]);
	
	$WorkerPoolHandler = new \WorkerPoolHandler($EVC);
	$WorkerPoolHandler->start();
}
else {
	header("HTTP/1.0 404 Not Found");
	die();
}
?>
