<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

if (empty($GLOBALS["UserSessionActivitiesHandler"])) {
	include_once $EVC->getModulePath("user/UserSessionActivitiesHandler", $EVC->getCommonProjectName());
	
	$GLOBALS["UserSessionActivitiesHandler"] = new \UserSessionActivitiesHandler($EVC, isset($_COOKIE[ \UserUtil::getConstantVariable("USER_SESSION_ID_VARIABLE_NAME") ]) ? $_COOKIE[ \UserUtil::getConstantVariable("USER_SESSION_ID_VARIABLE_NAME") ] : null);
}

$GLOBALS["logged_user"] = $GLOBALS["UserSessionActivitiesHandler"]->getUserData();
$GLOBALS["logged_user_id"] = isset($GLOBALS["logged_user"]["user_id"]) ? $GLOBALS["logged_user"]["user_id"] : null;
?>
