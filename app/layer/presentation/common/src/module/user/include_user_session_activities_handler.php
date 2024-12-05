<?php
if (empty($GLOBALS["UserSessionActivitiesHandler"])) {
	include_once $EVC->getModulePath("user/UserSessionActivitiesHandler", $EVC->getCommonProjectName());
	
	$GLOBALS["UserSessionActivitiesHandler"] = new \UserSessionActivitiesHandler($EVC, isset($_COOKIE[ \UserUtil::getConstantVariable("USER_SESSION_ID_VARIABLE_NAME") ]) ? $_COOKIE[ \UserUtil::getConstantVariable("USER_SESSION_ID_VARIABLE_NAME") ] : null);
}

$GLOBALS["logged_user"] = $GLOBALS["UserSessionActivitiesHandler"]->getUserData();
$GLOBALS["logged_user_id"] = isset($GLOBALS["logged_user"]["user_id"]) ? $GLOBALS["logged_user"]["user_id"] : null;
?>
