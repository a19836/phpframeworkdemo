<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/UserUtil", $common_project_name);
	
	$reserved_user_type_ids = UserUtil::getReservedUserTypeIds();
	
	if (in_array($_GET["user_type_id"], $reserved_user_type_ids)) {
		echo "This user type is native and cannot be deleted!";
	}
	else if (UserUtil::deleteUserType($brokers, $_GET["user_type_id"])) {
		echo "1";
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
