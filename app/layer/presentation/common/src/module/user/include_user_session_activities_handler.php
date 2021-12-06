<?php
if (!$GLOBALS["UserSessionActivitiesHandler"]) {
	include_once $EVC->getModulePath("user/UserSessionActivitiesHandler", $EVC->getCommonProjectName());
	
	$GLOBALS["UserSessionActivitiesHandler"] = new \UserSessionActivitiesHandler($EVC, $_COOKIE[ \UserUtil::getConstantVariable("USER_SESSION_ID_VARIABLE_NAME") ]);
}

$GLOBALS["logged_user"] = $GLOBALS["UserSessionActivitiesHandler"]->getUserData();
$GLOBALS["logged_user_id"] = $GLOBALS["logged_user"] ? $GLOBALS["logged_user"]["user_id"] : null;
?>
