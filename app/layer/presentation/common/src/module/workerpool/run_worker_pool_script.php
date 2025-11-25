<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

$is_cmd_line = isset($_SERVER['argc']) || (php_sapi_name() == 'cli' && empty($_SERVER["REMOTE_ADDRESS"])) || isset($_ENV['SSH_CLIENT'])/* || defined('STDIN')*/;

//Only execute this if command line. If someone try to access this file via http request, this file wno't do anything
if ($is_cmd_line) {
	include_once $EVC->getModulePath("workerpool/WorkerPoolHandler", $EVC->getCommonProjectName());
	
	//Force loglevel to what it is in the command line, even if the project as its own log level.
	$options = getopt("", array("loglevel::"));
	if (isset($options["loglevel"]) && is_numeric($options["loglevel"]) && !empty($GLOBALS["GlobalLogHandler"])) 
		$GLOBALS["GlobalLogHandler"]->setLogLevel($options["loglevel"]);
	
	$WorkerPoolHandler = new \WorkerPoolHandler($EVC);
	$WorkerPoolHandler->start();
}
else {
	header("HTTP/1.0 404 Not Found");
	die();
}
?>
