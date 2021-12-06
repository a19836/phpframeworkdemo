<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("user/UserUtil", $common_project_name);
	
	$reserved_user_type_ids = UserUtil::getReservedUserTypeIds();
	
	$data = array();
	$user_types = UserUtil::getAllUserTypes($brokers, true);
	
	if ($user_types) {
		$t = count($user_types);
		for ($i = 0; $i < $t; $i++)
			if (!in_array($user_types[$i]["user_type_id"], $reserved_user_type_ids))
				$data[] = $user_types[$i];
	}
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

echo $data ? json_encode($data) : "";
?>
