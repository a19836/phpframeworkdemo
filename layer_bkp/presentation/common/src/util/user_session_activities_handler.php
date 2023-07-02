<?php
/*
 * This file will be used in the modules or entities to check if the logged user has the necessary permission to continue...
 * This file only makes sense if the user module is installed.
 * The functions in this file will use the UserSessionActivitiesHandler class in the module/user/UserSessionActivitiesHandler.php file.
 */

function validateModuleUserActivity($EVC, $activity, $module_path) {
	if ($activity && $module_path) {
		initUserSessionActivitiesHandler($EVC);
		
		if ($GLOBALS["UserSessionActivitiesHandler"]) {
			return $GLOBALS["UserSessionActivitiesHandler"]->validateUserActivityByModule($activity, $module_path);
		}
	}
}

function validatePageUserActivity($EVC, $activity, $entity_path) {
	if ($activity && $module_path) {
		initUserSessionActivitiesHandler($EVC);
		
		if ($GLOBALS["UserSessionActivitiesHandler"]) {
			return $GLOBALS["UserSessionActivitiesHandler"]->validateUserActivityByPage($activity, $entity_path);
		}
	}
}

function moduleUserActivityExists($EVC, $activity, $module_path) {
	if ($activity && $module_path) {
		initUserSessionActivitiesHandler($EVC);
		
		if ($GLOBALS["UserSessionActivitiesHandler"]) {
			return $GLOBALS["UserSessionActivitiesHandler"]->userActivityExistsByModule($activity, $module_path);
		}
	}
}

function pageUserActivityExists($EVC, $activity, $entity_path) {
	if ($activity && $entity_path) {
		initUserSessionActivitiesHandler($EVC);
		
		if ($GLOBALS["UserSessionActivitiesHandler"]) {
			return $GLOBALS["UserSessionActivitiesHandler"]->userActivityExistsByPage($activity, $entity_path);
		}
	}
}

function initUserSessionActivitiesHandler($EVC) {
	if (!$GLOBALS["UserSessionActivitiesHandler"]) {
		$fp = $EVC->getModulePath("user/include_user_session_activities_handler", $EVC->getCommonProjectName());
		
		if (file_exists($fp)) {
			include $fp;
		}
	}
}
?>
