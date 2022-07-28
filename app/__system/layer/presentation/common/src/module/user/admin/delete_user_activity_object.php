<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/UserUtil", $common_project_name);
	
	if (UserUtil::deleteUserActivityObject($brokers, $_GET["thread_id"], $_GET["user_id"], $_GET["activity_id"], $_GET["object_type_id"], $_GET["object_id"], $_GET["time"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
