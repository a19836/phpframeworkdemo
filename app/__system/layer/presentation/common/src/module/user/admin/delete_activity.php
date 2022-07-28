<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/UserUtil", $common_project_name);
	
	$reserved_activity_ids = UserUtil::getReservedActivityIds();
	
	if (in_array($_GET["activity_id"], $reserved_activity_ids)) {
		echo "This activity is native and cannot be deleted!";
	}
	else if (UserUtil::deleteActivity($brokers, $_GET["activity_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
